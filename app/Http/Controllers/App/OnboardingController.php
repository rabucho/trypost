<?php

declare(strict_types=1);

namespace App\Http\Controllers\App;

use App\Actions\Billing\StartSubscriptionCheckout;
use App\Enums\Plan\Slug;
use App\Enums\User\Persona;
use App\Http\Requests\App\Onboarding\StoreOnboardingRequest;
use App\Models\Account;
use App\Models\Plan;
use App\Services\PostHogService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

class OnboardingController extends Controller
{
    public function index(Request $request): Response|RedirectResponse
    {
        if (config('trypost.self_hosted')) {
            return redirect()->route('app.calendar');
        }

        $user = $request->user();

        if ($user->account?->subscribed(Account::SUBSCRIPTION_NAME)) {
            return redirect()->route('app.calendar');
        }

        return Inertia::render('onboarding/Index', [
            'personas' => Persona::options(),
            'selected' => $user->persona?->value,
        ]);
    }

    public function store(
        StoreOnboardingRequest $request,
        PostHogService $postHog,
        StartSubscriptionCheckout $checkout,
    ): SymfonyResponse|RedirectResponse {
        if (config('trypost.self_hosted')) {
            return redirect()->route('app.calendar');
        }

        $user = $request->user();
        $account = $user->account;
        $persona = (string) $request->validated('persona');

        $user->update(['persona' => $persona]);

        $postHog->identify($user->id, [
            'persona' => $persona,
        ]);

        $plan = Plan::where('slug', Slug::Workspace)->firstOrFail();

        return $checkout->redirect(
            $account,
            (string) $plan->stripe_monthly_price_id,
            route('app.onboarding'),
        );
    }
}
