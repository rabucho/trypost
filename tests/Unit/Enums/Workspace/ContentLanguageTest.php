<?php

declare(strict_types=1);

use App\Enums\Workspace\ContentLanguage;

test('values exposes every supported content-language code', function () {
    expect(ContentLanguage::values())->toBe([
        'en', 'pt-BR', 'es', 'fr', 'de', 'it', 'nl',
        'pl', 'el', 'ja', 'ko', 'zh', 'ru', 'tr', 'ar',
    ]);
});

test('default language is English', function () {
    expect(ContentLanguage::DEFAULT)->toBe(ContentLanguage::English);
    expect(ContentLanguage::DEFAULT->value)->toBe('en');
});

test('options pairs each code with its native label', function () {
    $options = ContentLanguage::options();

    expect($options)->toHaveCount(count(ContentLanguage::cases()));
    expect($options[0])->toBe(['value' => 'en', 'label' => 'English']);
    expect($options)->toContain(['value' => 'pt-BR', 'label' => 'Português (Brasil)']);
    expect($options)->toContain(['value' => 'ja', 'label' => '日本語']);
});

test('english name is the language name in English for the AI image prompt', function () {
    expect(ContentLanguage::PortugueseBrazil->englishName())->toBe('Brazilian Portuguese');
    expect(ContentLanguage::French->englishName())->toBe('French');
    expect(ContentLanguage::Chinese->englishName())->toBe('Chinese');
});

test('fromHtmlLang resolves the two-letter primary subtag', function (string $lang, ?ContentLanguage $expected) {
    expect(ContentLanguage::fromHtmlLang($lang))->toBe($expected);
})->with([
    ['pt', ContentLanguage::PortugueseBrazil],
    ['pt-PT', ContentLanguage::PortugueseBrazil],
    ['en-US', ContentLanguage::English],
    ['es-MX', ContentLanguage::Spanish],
    ['fr', ContentLanguage::French],
    ['ja-JP', ContentLanguage::Japanese],
    ['zh-Hans', ContentLanguage::Chinese],
    ['sv', null],
    ['e', null],
    ['', null],
]);
