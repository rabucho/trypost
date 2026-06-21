<?php

declare(strict_types=1);

use App\Enums\User\Persona;

test('every persona has a non-empty label', function () {
    foreach (Persona::cases() as $persona) {
        expect($persona->label())->toBeString()->not->toBe('');
    }
});

test('options returns a value and label for every persona', function () {
    $options = Persona::options();

    expect($options)->toHaveCount(count(Persona::cases()))
        ->and($options[0])->toHaveKeys(['value', 'label'])
        ->and($options[0]['value'])->toBe(Persona::Creator->value);
});
