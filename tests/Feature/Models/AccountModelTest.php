<?php

declare(strict_types=1);

use App\Enums\Plan\Slug;
use App\Features\MonthlyCreditsLimit;
use App\Models\Account;
use App\Models\Plan;
use App\Models\Workspace;
use Illuminate\Support\Facades\DB;
use Laravel\Pennant\Feature;

test('forgetPlanFeatureCache drops the cached monthly credits feature', function () {
    $plan = Plan::where('slug', Slug::Workspace)->first();

    $account = Account::factory()->create(['plan_id' => $plan->id]);
    Workspace::factory()->create(['account_id' => $account->id]);

    Feature::for($account)->value(MonthlyCreditsLimit::class);

    expect(DB::table('features')->where('scope', 'account|'.$account->id)->count())
        ->toBe(1);

    $account->forgetPlanFeatureCache();

    expect(DB::table('features')->where('scope', 'account|'.$account->id)->count())
        ->toBe(0);

    expect(Feature::for($account)->value(MonthlyCreditsLimit::class))->toBe(2500);
});
