<?php

declare(strict_types=1);

namespace App\Observers;

use App\Actions\Automation\Trigger\DispatchPostTriggerAutomations;
use App\Enums\Automation\Trigger\Type as TriggerType;
use App\Enums\Post\Status as PostStatus;
use App\Models\Post;

class PostObserver
{
    public function __construct(private DispatchPostTriggerAutomations $dispatch) {}

    public function saved(Post $post): void
    {
        if (! $post->wasChanged('status')) {
            return;
        }

        $status = $post->status;

        if ($status === PostStatus::Published) {
            ($this->dispatch)($post, TriggerType::PostPublished);

            return;
        }

        if ($status === PostStatus::Scheduled) {
            ($this->dispatch)($post, TriggerType::PostScheduled);
        }
    }
}
