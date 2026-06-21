<?php

declare(strict_types=1);

test('the default trial length is 8 days', function () {
    expect(config('cashier.trial_days'))->toBe(8);
});
