<?php

declare(strict_types=1);

use App\Models\Account;
use Laravel\Cashier\Subscription;

test('syncWorkspaceQuantity does not touch Stripe in self-hosted mode', function () {
    config()->set('trypost.self_hosted', true);

    $subscription = mock(Subscription::class);
    $subscription->shouldReceive('active')->andReturnTrue();
    $subscription->shouldReceive('updateQuantity')->never();

    $account = mock(Account::class)->makePartial();
    $account->shouldReceive('subscription')->with(Account::SUBSCRIPTION_NAME)->andReturn($subscription);
    $account->shouldReceive('workspaces->count')->andReturn(3);

    $account->syncWorkspaceQuantity();
});

test('syncWorkspaceQuantity does not touch Stripe without an active subscription', function () {
    config()->set('trypost.self_hosted', false);

    $subscription = mock(Subscription::class);
    $subscription->shouldReceive('active')->andReturnFalse();
    $subscription->shouldReceive('updateQuantity')->never();

    $account = mock(Account::class)->makePartial();
    $account->shouldReceive('subscription')->with(Account::SUBSCRIPTION_NAME)->andReturn($subscription);

    $account->syncWorkspaceQuantity();
});

test('syncWorkspaceQuantity updates the subscription quantity to the workspace count', function () {
    config()->set('trypost.self_hosted', false);

    $subscription = mock(Subscription::class);
    $subscription->shouldReceive('active')->andReturnTrue();
    $subscription->shouldReceive('updateQuantity')->once()->with(3);

    $account = mock(Account::class)->makePartial();
    $account->shouldReceive('subscription')->with(Account::SUBSCRIPTION_NAME)->andReturn($subscription);
    $account->shouldReceive('workspaces->count')->andReturn(3);

    $account->syncWorkspaceQuantity();
});
