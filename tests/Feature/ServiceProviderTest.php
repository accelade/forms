<?php

declare(strict_types=1);

it('registers the service provider', function () {
    expect(config('forms'))->toBeArray();
});

it('has default configuration values', function () {
    expect(config('forms.default_method'))->toBe('POST')
        ->and(config('forms.errors.show_inline'))->toBeTrue()
        ->and(config('forms.errors.scroll_to_first'))->toBeTrue();
});

it('registers views', function () {
    $finder = app('view')->getFinder();
    $hints = $finder->getHints();

    expect($hints)->toHaveKey('forms');
});
