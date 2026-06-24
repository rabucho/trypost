<?php

declare(strict_types=1);

use App\Enums\Media\Type;

test('media type has correct values', function () {
    expect(Type::Image->value)->toBe('image');
    expect(Type::Video->value)->toBe('video');
    expect(Type::Document->value)->toBe('document');
});

test('media type has labels', function () {
    expect(Type::Image->label())->toBe('Imagem');
    expect(Type::Video->label())->toBe('Vídeo');
    expect(Type::Document->label())->toBe('Documento');
});

test('media type has allowed mime types', function () {
    expect(Type::Image->allowedMimeTypes())->toContain('image/jpeg', 'image/png');
    expect(Type::Video->allowedMimeTypes())->toContain('video/mp4', 'video/quicktime');
    expect(Type::Document->allowedMimeTypes())->toBe(['application/pdf']);
});

test('media type document accepts only the pdf extension', function () {
    expect(Type::Document->extensions())->toBe(['pdf']);
});

test('media type max size in mb is read from config', function () {
    config(['trypost.media.max_size_mb.image' => 10]);
    config(['trypost.media.max_size_mb.video' => 1024]);

    expect(Type::Image->maxSizeInMb())->toBe(10);
    expect(Type::Video->maxSizeInMb())->toBe(1024);
});

test('media type exposes derived size units', function () {
    config(['trypost.media.max_size_mb.video' => 1024]);

    expect(Type::Video->maxSizeInKb())->toBe(1024 * 1024);
    expect(Type::Video->maxSizeInBytes())->toBe(1024 * 1024 * 1024);
});

test('media type resolves from mime', function () {
    expect(Type::fromMime('image/jpeg'))->toBe(Type::Image);
    expect(Type::fromMime('video/mp4'))->toBe(Type::Video);
    expect(Type::fromMime('application/pdf'))->toBe(Type::Document);
    expect(Type::fromMime('not-a-mime'))->toBeNull();
});

test('media type document max size in mb is read from config', function () {
    config(['trypost.media.max_size_mb.document' => 100]);

    expect(Type::Document->maxSizeInMb())->toBe(100);
});
