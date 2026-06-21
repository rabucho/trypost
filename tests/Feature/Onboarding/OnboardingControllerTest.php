<?php

declare(strict_types=1);

use App\Actions\Billing\StartSubscriptionCheckout;
use App\Enums\Plan\Slug;
use App\Enums\User\Persona;
use App\Jobs\PostHog\SendEvent;
use App\Models\Account;
use App\Models\Plan;
use App\Models\User;
use Illuminate\Support\Facades\Bus;

beforeEach(function () {
    config(['trypost.self_hosted' => false]);
    $this->user = User::factory()->create();
});

test('onboarding renders the persona selection for an unsubscribed account', function () {
    $response = $this->actingAs($this->user)->get(route('app.onboarding'));

    $response->assertOk();
    $response->assertInertia(fn ($page) => $page
        ->component('onboarding/Index')
        ->has('personas', count(Persona::cases()))
    );
});

test('onboarding redirects to calendar in self-hosted mode', function () {
    config(['trypost.self_hosted' => true]);

    $response = $this->actingAs($this->user)->get(route('app.onboarding'));

    $response->assertRedirect(route('app.calendar'));
});

test('onboarding redirects to calendar when already subscribed', function () {
    $this->user->account->subscriptions()->create([
        'type' => Account::SUBSCRIPTION_NAME,
        'stripe_id' => 'sub_'.fake()->uuid(),
        'stripe_status' => 'active',
        'stripe_price' => 'price_123',
    ]);

    $response = $this->actingAs($this->user->fresh())->get(route('app.onboarding'));

    $response->assertRedirect(route('app.calendar'));
});

test('onboarding store rejects an invalid persona', function () {
    $response = $this->actingAs($this->user)->post(route('app.onboarding.store'), [
        'persona' => 'not-a-persona',
    ]);

    $response->assertSessionHasErrors('persona');
    expect($this->user->fresh()->persona)->toBeNull();
});

test('onboarding store requires a persona', function () {
    $response = $this->actingAs($this->user)->post(route('app.onboarding.store'), []);

    $response->assertSessionHasErrors('persona');
});

test('onboarding store does nothing in self-hosted mode', function () {
    config(['trypost.self_hosted' => true]);

    $response = $this->actingAs($this->user)->post(route('app.onboarding.store'), [
        'persona' => Persona::Agency->value,
    ]);

    $response->assertRedirect(route('app.calendar'));
    expect($this->user->fresh()->persona)->toBeNull();
});

test('onboarding store saves the persona, mirrors to PostHog and starts monthly checkout', function () {
    config(['services.posthog.enabled' => true, 'services.posthog.api_key' => 'phc_test']);
    Bus::fake();

    Plan::where('slug', Slug::Workspace)->firstOrFail()->update([
        'stripe_monthly_price_id' => 'price_monthly_test',
        'stripe_yearly_price_id' => 'price_yearly_test',
    ]);

    $this->mock(StartSubscriptionCheckout::class)
        ->shouldReceive('redirect')
        ->once()
        ->withArgs(fn (Account $account, string $priceId, string $cancelUrl): bool => $priceId === 'price_monthly_test')
        ->andReturn(redirect()->route('app.calendar'));

    $response = $this->actingAs($this->user)->post(route('app.onboarding.store'), [
        'persona' => Persona::Agency->value,
    ]);

    $response->assertRedirect(route('app.calendar'));
    expect($this->user->fresh()->persona)->toBe(Persona::Agency);

    Bus::assertDispatched(SendEvent::class);
});
