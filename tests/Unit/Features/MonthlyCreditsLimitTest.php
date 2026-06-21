<?php

declare(strict_types=1);

use App\Enums\Plan\Slug;
use App\Features\MonthlyCreditsLimit;
use App\Models\Account;
use App\Models\Plan;
use App\Models\Workspace;

test('resolves to the billing cycle credit allotment', function () {
    $plan = Plan::where('slug', Slug::Workspace)->first();
    $account = Account::factory()->create(['plan_id' => $plan->id, 'trial_ends_at' => null]);
    Workspace::factory()->count(2)->create(['account_id' => $account->id]);

    expect((new MonthlyCreditsLimit)->resolve($account))->toBe(5000);
});

test('resolves to zero when the account has no workspaces', function () {
    $plan = Plan::where('slug', Slug::Workspace)->first();
    $account = Account::factory()->create(['plan_id' => $plan->id, 'trial_ends_at' => null]);

    expect((new MonthlyCreditsLimit)->resolve($account))->toBe(0);
});
