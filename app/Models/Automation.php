<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\Automation\Status;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Crypt;
use Throwable;

class Automation extends Model
{
    use HasFactory;
    use HasUuids;

    /**
     * Fields inside `nodes[].data` that hold sensitive credentials. Stored
     * encrypted on save and never returned to the frontend in plain text —
     * AutomationResource masks them with PLACEHOLDER on output.
     */
    public const SENSITIVE_NODE_FIELDS = ['auth_token', 'auth_password'];

    public const SENSITIVE_PLACEHOLDER = '••••••••';

    protected $guarded = [];

    protected $casts = [
        'status' => Status::class,
        'nodes' => 'array',
        'connections' => 'array',
        'activated_at' => 'datetime',
        'paused_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::saving(function (self $automation): void {
            $automation->nodes = self::encryptSensitiveFields(
                $automation->nodes ?? [],
                $automation->getOriginal('nodes') ?? [],
            );
        });
    }

    public function workspace(): BelongsTo
    {
        return $this->belongsTo(Workspace::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function triggerItems(): HasMany
    {
        return $this->hasMany(AutomationTriggerItem::class);
    }

    public function runs(): HasMany
    {
        return $this->hasMany(AutomationRun::class);
    }

    /**
     * Walks both the incoming and stored node lists and reconciles sensitive
     * fields: a PLACEHOLDER value means "user didn't change it" (frontend
     * never received the real value) so we keep the existing ciphertext. Plain
     * text values get encrypted; already-encrypted strings pass through.
     *
     * @param  array<int, array<string, mixed>>  $incoming
     * @param  array<int, array<string, mixed>>|string  $original
     * @return array<int, array<string, mixed>>
     */
    private static function encryptSensitiveFields(array $incoming, array|string $original): array
    {
        $original = is_array($original) ? $original : (json_decode($original, true) ?: []);
        $originalById = collect($original)->keyBy('id');

        foreach ($incoming as &$node) {
            $originalNode = $originalById->get($node['id'] ?? null);
            foreach (self::SENSITIVE_NODE_FIELDS as $field) {
                $value = data_get($node, "data.{$field}");
                if (! is_string($value) || $value === '') {
                    continue;
                }
                if ($value === self::SENSITIVE_PLACEHOLDER) {
                    data_set($node, "data.{$field}", data_get($originalNode, "data.{$field}", ''));

                    continue;
                }
                if (self::looksEncrypted($value)) {
                    continue;
                }
                data_set($node, "data.{$field}", Crypt::encryptString($value));
            }
        }

        return $incoming;
    }

    /**
     * Quick check for Laravel's `Crypt::encryptString` output without paying
     * the cost of a full decrypt attempt. Laravel wraps payloads as base64
     * JSON beginning with the canonical `eyJpdiI` ("{"iv":"...) prefix.
     */
    private static function looksEncrypted(string $value): bool
    {
        if (! str_starts_with($value, 'eyJ')) {
            return false;
        }
        try {
            Crypt::decryptString($value);

            return true;
        } catch (Throwable) {
            return false;
        }
    }
}
