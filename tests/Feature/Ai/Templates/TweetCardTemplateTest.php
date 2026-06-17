<?php

declare(strict_types=1);

use App\Ai\Templates\TweetCardTemplate;

test('tweet card template identity', function () {
    $t = new TweetCardTemplate;
    expect($t->key())->toBe('tweet_card')
        ->and($t->needsAccount())->toBeTrue()
        ->and($t->generatorFormat())->toBe('tweet_card')
        ->and($t->promptView())->toBe('prompts.post_content.tweet_card');
});
