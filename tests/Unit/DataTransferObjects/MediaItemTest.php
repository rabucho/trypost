<?php

declare(strict_types=1);

use App\DataTransferObjects\MediaItem;

test('fromArray backfills the mime type from the path extension when missing', function () {
    expect(MediaItem::fromArray(['path' => 'a/b/photo.JPG'])->mime_type)->toBe('image/jpeg');
    expect(MediaItem::fromArray(['path' => 'clip.mp4'])->mime_type)->toBe('video/mp4');
    expect(MediaItem::fromArray(['path' => 'clip.mov'])->mime_type)->toBe('video/quicktime');
    expect(MediaItem::fromArray(['path' => 'deck.pdf'])->mime_type)->toBe('application/pdf');
    expect(MediaItem::fromArray(['path' => 'archive.zip'])->mime_type)->toBeNull();
});

test('fromArray keeps an explicit mime type over the extension', function () {
    $item = MediaItem::fromArray(['path' => 'thing.png', 'mime_type' => 'video/mp4']);

    expect($item->mime_type)->toBe('video/mp4');
});

test('media item classifies its type via the Type enum', function () {
    expect(MediaItem::fromArray(['path' => 'x.png'])->isImage())->toBeTrue();
    expect(MediaItem::fromArray(['path' => 'x.mp4'])->isVideo())->toBeTrue();
    expect(MediaItem::fromArray(['path' => 'x.pdf'])->isDocument())->toBeTrue();

    $pdf = MediaItem::fromArray(['path' => 'x.pdf']);
    expect($pdf->isImage())->toBeFalse();
    expect($pdf->isVideo())->toBeFalse();
});

test('media item falls back to the extension when no mime is present', function () {
    // A stored heic photo (not in the upload allow-list) still classifies as an image.
    $item = new MediaItem(id: '1', path: 'photo.heic', url: 'https://x/p.heic', mime_type: null);

    expect($item->isImage())->toBeTrue();
});
