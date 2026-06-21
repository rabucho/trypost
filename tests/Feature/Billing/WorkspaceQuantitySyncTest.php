<?php

declare(strict_types=1);

use App\Models\Account;
use Laravel\Cashier\Subscription;

test('syncWorkspaceQuantity does not touch Stripe in self-hosted mode', function () {
    config()->set('trypost.self_hosted', true);

    $subscription = Mockery::mock(Subscription::class);
    $subscription->shouldReceive('active')->andReturnTrue();
    $subscription->shouldReceive('updateQuantity')->never();

    $account = Mockery::mock(Account::class)->makePartial();
    $account->shouldReceive('subscription')->andReturn($subscription);
    $account->shouldReceive('workspaces->count')->andReturn(3);

    $account->syncWorkspaceQuantity();
});

test('syncWorkspaceQuantity does not touch Stripe without an active subscription', function () {
    config()->set('trypost.self_hosted', false);

    $subscription = Mockery::mock(Subscription::class);
    $subscription->shouldReceive('active')->andReturnFalse();
    $subscription->shouldReceive('updateQuantity')->never();

    $account = Mockery::mock(Account::class)->makePartial();
    $account->shouldReceive('subscription')->with(Account::SUBSCRIPTION_NAME)->andReturn($subscription);

    $account->syncWorkspaceQuantity();
});

test('syncWorkspaceQuantity updates the subscription quantity to the workspace count', function () {
    config()->set('trypost.self_hosted', false);

    $subscription = Mockery::mock(Subscription::class);
    $subscription->shouldReceive('active')->andReturnTrue();
    $subscription->shouldReceive('updateQuantity')->once()->with(3);

    $account = Mockery::mock(Account::class)->makePartial();
    $account->shouldReceive('subscription')->with(Account::SUBSCRIPTION_NAME)->andReturn($subscription);
    $account->shouldReceive('workspaces->count')->andReturn(3);

    $account->syncWorkspaceQuantity();
});
