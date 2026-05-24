<?php

declare(strict_types=1);

namespace App\Services\Automation;

use Illuminate\Support\Carbon;

class ExpressionResolver
{
    public function resolve(string $template, array $context): string
    {
        return preg_replace_callback(
            '/\{\{\s*([a-zA-Z0-9_.]+)\s*\}\}/',
            fn ($matches) => $this->resolveVariable($matches[1], $context),
            $template,
        );
    }

    private function resolveVariable(string $path, array $context): string
    {
        if ($path === 'now') {
            return Carbon::now()->toIso8601String();
        }

        if ($path === 'today') {
            return Carbon::today()->toDateString();
        }

        $value = data_get($context, $path);

        if ($value === null) {
            return '';
        }

        if (is_scalar($value)) {
            return (string) $value;
        }

        return json_encode($value);
    }
}
