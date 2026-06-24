<?php

declare(strict_types=1);

namespace App\Rules;

use App\Enums\Media\Type as MediaType;
use App\Enums\PostPlatform\ContentType;
use App\Models\Post;
use Closure;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Translation\PotentiallyTranslatedString;
use Illuminate\Validation\ValidationException;

class ContentTypeCompatibleWithMedia implements DataAwareRule, ValidationRule
{
    /**
     * @var array<string, mixed>
     */
    private array $data = [];

    /**
     * @param  array<int, array<string, mixed>>|null  $fallbackMedia  Stored media used
     *                                                                when the request omits the `media` key entirely — lets API/MCP partial
     *                                                                updates (which don't resubmit media) validate a content_type against the
     *                                                                post's already-stored media.
     */
    public function __construct(private ?array $fallbackMedia = null) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public function setData(array $data): static
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Validate every enabled platform's stored content_type against the post's
     * stored media. Used by publish flows that don't resubmit media (e.g. the
     * MCP publish tool) — the media-side mirror of
     * PostPlatformMetaRules::assertStoredPostPublishable().
     *
     * @throws ValidationException
     */
    public static function assertStoredPostCompatible(Post $post): void
    {
        $media = (array) ($post->media ?? []);
        $errors = [];

        foreach ($post->postPlatforms()->where('enabled', true)->get()->values() as $index => $postPlatform) {
            (new self($media))->validate(
                "platforms.{$index}.content_type",
                (string) $postPlatform->content_type?->value,
                function (string $message) use (&$errors, $index): void {
                    $errors["platforms.{$index}.content_type"] = $message;
                },
            );
        }

        if ($errors !== []) {
            throw ValidationException::withMessages($errors);
        }
    }

    /**
     * @param  Closure(string, ?string=): PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $contentType = ContentType::tryFrom((string) $value);
        if (! $contentType) {
            return;
        }

        // Use the request's media when present; otherwise fall back to the
        // post's stored media so partial publish/schedule updates still validate.
        $media = array_key_exists('media', $this->data)
            ? (array) data_get($this->data, 'media', [])
            : (array) ($this->fallbackMedia ?? []);
        $count = count($media);

        if ($contentType->requiresMedia() && $count === 0) {
            $fail("{$contentType->label()} requires at least one media file.");

            return;
        }

        if ($count === 0) {
            return;
        }

        $hasImage = collect($media)->contains(fn ($item) => $this->isImage((array) $item));
        $hasVideo = collect($media)->contains(fn ($item) => $this->isVideo((array) $item));
        $hasDocument = collect($media)->contains(fn ($item) => $this->isDocument((array) $item));

        if ($hasImage && ! $contentType->supportsImage()) {
            $fail("{$contentType->label()} does not support images.");
        }

        if ($hasVideo && ! $contentType->supportsVideo()) {
            $fail("{$contentType->label()} does not support videos.");
        }

        if ($hasDocument && ! $contentType->supportsDocument()) {
            $fail("{$contentType->label()} does not support PDF documents.");
        }

        if ($hasImage && $hasVideo && ! $contentType->supportsMixedMedia()) {
            $fail("{$contentType->label()} can't combine an image and a video in the same post.");
        }
    }

    /**
     * @param  array<string, mixed>  $item
     */
    private function isImage(array $item): bool
    {
        if (data_get($item, 'type') === MediaType::Image->value) {
            return true;
        }

        return str_starts_with((string) data_get($item, 'mime_type', ''), 'image/');
    }

    /**
     * @param  array<string, mixed>  $item
     */
    private function isVideo(array $item): bool
    {
        if (data_get($item, 'type') === MediaType::Video->value) {
            return true;
        }

        return str_starts_with((string) data_get($item, 'mime_type', ''), 'video/');
    }

    /**
     * @param  array<string, mixed>  $item
     */
    private function isDocument(array $item): bool
    {
        if (data_get($item, 'type') === MediaType::Document->value) {
            return true;
        }

        return data_get($item, 'mime_type') === 'application/pdf';
    }
}
