<?php

declare(strict_types=1);

use App\Support\PostMediaRules;

test('hosted media rules require id and path and allow source', function () {
    $rules = PostMediaRules::rules(hosted: true);

    expect($rules['media.*.id'])->toContain('required')
        ->and($rules['media.*.path'])->toContain('required')
        ->and($rules)->toHaveKey('media.*.source')
        ->and($rules)->toHaveKey('media.*.source_meta');
});

test('api media rules accept a bare external url and omit source', function () {
    $rules = PostMediaRules::rules(hosted: false);

    expect($rules['media.*.id'])->toContain('nullable')
        ->and($rules['media.*.url'])->toContain('url:http,https')
        ->and($rules)->not->toHaveKey('media.*.source')
        ->and($rules)->not->toHaveKey('media.*.source_meta');
});

test('both variants keep the shared item keys so validated() preserves them', function () {
    foreach ([true, false] as $hosted) {
        expect(PostMediaRules::rules(hosted: $hosted))->toHaveKeys([
            'media.*.id',
            'media.*.path',
            'media.*.url',
            'media.*.type',
            'media.*.mime_type',
            'media.*.original_filename',
            'media.*.size',
            'media.*.meta',
        ]);
    }
});
