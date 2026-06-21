<?php

declare(strict_types=1);

namespace App\Enums\User;

enum Persona: string
{
    case Creator = 'creator';
    case Freelancer = 'freelancer';
    case Startup = 'startup';
    case Agency = 'agency';
    case SmallBusiness = 'small_business';
    case Other = 'other';

    public function label(): string
    {
        return match ($this) {
            self::Creator => 'Creator',
            self::Freelancer => 'Freelancer',
            self::Startup => 'Startup',
            self::Agency => 'Agency',
            self::SmallBusiness => 'Small business',
            self::Other => 'Other',
        };
    }

    /**
     * @return array<int, array{value: string, label: string}>
     */
    public static function options(): array
    {
        return array_map(
            fn (self $persona): array => ['value' => $persona->value, 'label' => $persona->label()],
            self::cases(),
        );
    }
}
