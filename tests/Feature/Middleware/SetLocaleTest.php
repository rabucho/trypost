<?php

declare(strict_types=1);

use App\Http\Middleware\App\SetLocale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

/**
 * Run the middleware with the given `locale` cookie (null = no cookie) and
 * return the shared `htmlDir` the Blade root view renders into `<html dir>`.
 */
function runSetLocale(?string $locale): string
{
    $request = Request::create('/', 'GET');

    if ($locale !== null) {
        $request->cookies->set('locale', $locale);
    }

    (new SetLocale)->handle($request, fn () => response('ok'));

    return View::shared('htmlDir');
}

test('shares rtl direction and sets the locale for Arabic', function () {
    expect(runSetLocale('ar'))->toBe('rtl');
    expect(app()->getLocale())->toBe('ar');
});

test('shares ltr direction for a left-to-right locale', function (string $locale) {
    expect(runSetLocale($locale))->toBe('ltr');
    expect(app()->getLocale())->toBe($locale);
})->with(['en', 'ja', 'pt-BR', 'de']);

test('falls back to the default locale and ltr for an unknown cookie', function () {
    expect(runSetLocale('sv'))->toBe('ltr');
    expect(app()->getLocale())->toBe(config('languages.default'));
});

test('falls back to the default locale and ltr when no cookie is set', function () {
    expect(runSetLocale(null))->toBe('ltr');
    expect(app()->getLocale())->toBe(config('languages.default'));
});
