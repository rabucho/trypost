<?php

declare(strict_types=1);

namespace App\DataTransferObjects;

use App\Enums\Media\Source;
use App\Enums\Media\Type;

class MediaItem
{
    /**
     * @param  array<string, mixed>|null  $source_meta
     */
    public function __construct(
        public readonly string $id,
        public readonly string $path,
        public readonly string $url,
        public readonly ?string $mime_type = null,
        public readonly ?string $original_filename = null,
        public readonly ?Source $source = null,
        public readonly ?array $source_meta = null,
    ) {}

    public function isVideo(): bool
    {
        return Type::classify($this->mime_type, $this->path) === Type::Video;
    }

    public function isImage(): bool
    {
        return Type::classify($this->mime_type, $this->path) === Type::Image;
    }

    public function isDocument(): bool
    {
        return Type::classify($this->mime_type, $this->path) === Type::Document;
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        $path = data_get($data, 'path', '');
        $mimeType = data_get($data, 'mime_type');

        if (! $mimeType && $path) {
            $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));
            $mimeType = match ($extension) {
                'jpg', 'jpeg' => 'image/jpeg',
                'png' => 'image/png',
                'gif' => 'image/gif',
                'webp' => 'image/webp',
                'mp4' => 'video/mp4',
                'mov' => 'video/quicktime',
                'pdf' => 'application/pdf',
                default => null,
            };
        }

        $sourceValue = data_get($data, 'source');
        $source = is_string($sourceValue) ? Source::tryFrom($sourceValue) : null;

        $sourceMeta = data_get($data, 'source_meta');

        return new self(
            id: data_get($data, 'id', ''),
            path: $path,
            url: data_get($data, 'url', ''),
            mime_type: $mimeType,
            original_filename: data_get($data, 'original_filename'),
            source: $source,
            source_meta: is_array($sourceMeta) ? $sourceMeta : null,
        );
    }
}
