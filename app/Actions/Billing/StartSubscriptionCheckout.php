<?php

declare(strict_types=1);

namespace App\Actions\Billing;

use App\Models\Account;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\Response;

class StartSubscriptionCheckout
{
    /**
     * Create a Stripe Checkout session for the given price and return an Inertia
     * redirect to it. Quantity tracks the account's workspace count; a trial is
     * attached when the instance requires a card up front.
     */
    public function redirect(Account $account, string $priceId, string $cancelUrl): Response
    {
        $account->createOrGetStripeCustomer([
            'email' => $account->stripeEmail(),
            'name' => $account->stripeName(),
        ]);

        $subscription = $account->newSubscription(Account::SUBSCRIPTION_NAME, $priceId)
            ->quantity(max(1, $account->workspaces()->count()))
            ->allowPromotionCodes();

        if ((bool) config('trypost.billing.require_card_for_trial', true)) {
            $subscription->trialDays(config('cashier.trial_days'));
        }

        $session = $subscription->checkout([
            'success_url' => route('app.billing.processing').'?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => $cancelUrl,
        ]);

        return Inertia::location($session->url);
    }
}
