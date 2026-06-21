<?php

return [
    'title' => 'Billing',

    'past_due_notice' => [
        'title' => 'Payment past due',
        'description' => 'Update your payment method to keep your subscription active.',
        'cta' => 'Update payment',
    ],

    'annual_banner' => [
        'title' => 'Save 2 months with annual billing',
        'description' => 'Switch to annual and get 2 months free — pay less every month.',
        'cta' => 'Upgrade to annual',
    ],

    'subscribe' => [
        'page_title' => 'Choose your plan',
        'eyebrow' => 'Pricing',
        'title' => 'Choose the right plan for you',
        'description' => 'Pick the plan that fits you. Billed monthly or annually.',
        'trial_info' => ':days-day free trial, then billed automatically',
        'monthly' => 'Monthly',
        'yearly' => 'Yearly',
        'per_month' => 'monthly',
        'per_year' => 'yearly',
        'billed_monthly' => 'Billed monthly',
        'billed_yearly' => 'Billed annually',
        'features_included' => "What's included:",
        'everything_in' => 'Everything in :plan, plus:',
        'save_months' => '2 months free',
        'popular' => 'Most popular',
        'start_trial' => 'Start :days-day free trial',
        'subscribe_cta' => 'Subscribe',
        'prices' => [
            'workspace' => ['monthly' => '$12', 'yearly_per_month' => '$10', 'yearly' => '$120'],
        ],
        'features' => [
            'per_workspace_credits' => ':count AI credits per workspace/mo',
            'one_per_network' => 'One account per social network',
            'unlimited_members' => 'Unlimited team members',
            'all_platforms' => 'Publish to every supported platform',
        ],
        'credit_tooltips' => [
            'workspace' => 'Each workspace includes 2,500 AI credits per month — about 160 AI images or 280 AI text generations.',
        ],
    ],

    'plan' => [
        'title' => 'Plan',
        'description' => 'Manage your subscription plan.',
        'label' => 'Plan',
        'workspaces' => '{1}:count workspace|[2,*]:count workspaces',
        'per_workspace' => 'per workspace',
        'price' => 'Price',
        'month' => 'month',
        'trial' => 'Trial',
        'active' => 'Active',
        'past_due' => 'Past due',
        'cancelling' => 'Cancelling',
        'trial_ends' => 'Trial ends',
    ],

    'subscription' => [
        'title' => 'Subscription',
        'description' => 'Manage your payment method, billing details, and subscription.',
        'payment_method' => 'Payment method',
        'no_payment_method' => 'No payment method on file yet.',
        'expires_on' => 'Expires :month/:year',
        'manage_label' => 'Subscription',
        'manage_stripe' => 'Manage on Stripe',
    ],

    'invoices' => [
        'title' => 'Invoices',
        'description' => 'Download your past invoices.',
        'empty' => 'No invoices found',
        'paid' => 'Paid',
    ],

    'flash' => [
        'plan_changed' => 'You are now on the :plan plan.',
        'switched_to_yearly' => 'You\'re now on annual billing.',
        'cannot_manage' => 'Only the account owner can manage billing.',
        'credits_exhausted' => 'Out of AI credits — your monthly :limit allowance has been used. Upgrade your plan or wait until next month.',
        'subscription_required' => 'An active subscription is required to use AI features.',
    ],

    'processing' => [
        'page_title' => 'Processing...',
        'title' => 'Processing your subscription',
        'description' => 'Please wait while we set up your account. This will only take a moment.',
        'success_title' => 'You\'re all set!',
        'success_description' => 'Your subscription is active. Redirecting you to your workspaces...',
        'cancelled_title' => 'Checkout cancelled',
        'cancelled_description' => 'Your checkout was cancelled. No charges were made.',
        'retry' => 'Try again',
    ],
];
