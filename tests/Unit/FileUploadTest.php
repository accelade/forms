<?php

declare(strict_types=1);

use Accelade\Forms\Components\FileUpload;

it('can create a file upload field', function () {
    $field = FileUpload::make('avatar');

    expect($field->getName())->toBe('avatar')
        ->and($field->getId())->toBe('avatar');
});

it('can set accepted file types', function () {
    $field = FileUpload::make('document')
        ->acceptedFileTypes(['application/pdf', 'application/msword']);

    expect($field->getAcceptedFileTypes())->toBe('application/pdf,application/msword');
});

it('can use accept() alias', function () {
    $field = FileUpload::make('document')
        ->accept('application/pdf');

    expect($field->getAcceptedFileTypes())->toBe('application/pdf');
});

it('can set max and min file size', function () {
    $field = FileUpload::make('document')
        ->minSize(100)
        ->maxSize(5120);

    expect($field->getMinSize())->toBe(100)
        ->and($field->getMaxSize())->toBe(5120);
});

it('can enable multiple uploads', function () {
    $field = FileUpload::make('documents')->multiple();

    expect($field->isMultiple())->toBeTrue();
});

it('can set max and min files', function () {
    $field = FileUpload::make('documents')
        ->minFiles(1)
        ->maxFiles(5);

    expect($field->getMinFiles())->toBe(1)
        ->and($field->getMaxFiles())->toBe(5)
        ->and($field->isMultiple())->toBeTrue();
});

it('can restrict to images only', function () {
    $field = FileUpload::make('photo')->image();

    expect($field->isImage())->toBeTrue()
        ->and($field->getAcceptedFileTypes())->toBe('image/*');
});

it('can set storage directory and disk', function () {
    $field = FileUpload::make('avatar')
        ->directory('avatars')
        ->disk('public');

    expect($field->getDirectory())->toBe('avatars')
        ->and($field->getDisk())->toBe('public');
});

it('is previewable by default', function () {
    $field = FileUpload::make('avatar');

    expect($field->isPreviewable())->toBeTrue();
});

it('can disable preview, download, and delete', function () {
    $field = FileUpload::make('document')
        ->previewable(false)
        ->downloadable(false)
        ->deletable(false);

    expect($field->isPreviewable())->toBeFalse()
        ->and($field->isDownloadable())->toBeFalse()
        ->and($field->isDeletable())->toBeFalse();
});

// Splade compatibility - FilePond
it('does not have filepond enabled by default', function () {
    $field = FileUpload::make('avatar');

    expect($field->hasFilepond())->toBeFalse();
});

it('can enable filepond', function () {
    $field = FileUpload::make('avatar')->filepond();

    expect($field->hasFilepond())->toBeTrue()
        ->and($field->getFilepondOptions())->toBe([]);
});

it('can enable filepond with custom options', function () {
    $field = FileUpload::make('avatar')
        ->filepond(['allowImagePreview' => true]);

    expect($field->hasFilepond())->toBeTrue()
        ->and($field->getFilepondOptions())->toBe(['allowImagePreview' => true]);
});

it('can set default filepond options globally', function () {
    FileUpload::defaultFilepond(['maxFileSize' => '5MB']);

    $field = FileUpload::make('avatar')->filepond();

    expect($field->getFilepondOptions())->toBe(['maxFileSize' => '5MB']);

    // Reset for other tests
    FileUpload::defaultFilepond([]);
});

// Splade compatibility - Preview
it('does not have preview enabled by default', function () {
    $field = FileUpload::make('avatar');

    expect($field->hasPreview())->toBeFalse();
});

it('can enable preview', function () {
    $field = FileUpload::make('avatar')->preview();

    expect($field->hasPreview())->toBeTrue();
});

// Splade compatibility - Image dimensions
it('can set image dimension constraints', function () {
    $field = FileUpload::make('photo')
        ->minWidth(100)
        ->maxWidth(1920)
        ->minHeight(100)
        ->maxHeight(1080);

    expect($field->getMinWidth())->toBe(100)
        ->and($field->getMaxWidth())->toBe(1920)
        ->and($field->getMinHeight())->toBe(100)
        ->and($field->getMaxHeight())->toBe(1080);
});

it('can set exact image dimensions', function () {
    $field = FileUpload::make('photo')
        ->width(800)
        ->height(600);

    expect($field->getWidth())->toBe(800)
        ->and($field->getHeight())->toBe(600);
});

it('can set dimensions using convenience method', function () {
    $field = FileUpload::make('photo')
        ->dimensions(1024, 768);

    expect($field->getWidth())->toBe(1024)
        ->and($field->getHeight())->toBe(768);
});

it('can set image resolution constraints', function () {
    $field = FileUpload::make('photo')
        ->minResolution(100000)
        ->maxResolution(2000000);

    expect($field->getMinResolution())->toBe(100000)
        ->and($field->getMaxResolution())->toBe(2000000);
});

it('can set all dimension constraints at once', function () {
    $field = FileUpload::make('photo')
        ->imageDimensions(100, 1920, 100, 1080);

    expect($field->getMinWidth())->toBe(100)
        ->and($field->getMaxWidth())->toBe(1920)
        ->and($field->getMinHeight())->toBe(100)
        ->and($field->getMaxHeight())->toBe(1080);
});
