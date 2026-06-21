<?php

declare(strict_types=1);

namespace App\Features;

use App\Models\Account;
use App\Support\BillingCycle;

class MonthlyCreditsLimit
{
    public string $name = 'monthly-credits-limit';

    public function resolve(Account $scope): int
    {
        return BillingCycle::for($scope)->creditAllotment();
    }
}
