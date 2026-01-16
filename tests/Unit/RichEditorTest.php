<?php

declare(strict_types=1);

use Accelade\Forms\Components\RichEditor;

it('can create a rich editor', function () {
    $field = RichEditor::make('content');

    expect($field->getName())->toBe('content')
        ->and($field->getId())->toBe('content');
});

it('has default toolbar buttons', function () {
    $field = RichEditor::make('content');

    $flatButtons = $field->getFlatToolbarButtons();

    expect($flatButtons)->toContain('bold', 'italic', 'link');
});

it('can set toolbar buttons as flat array', function () {
    $field = RichEditor::make('content')
        ->toolbarButtons(['bold', 'italic']);

    expect($field->getToolbarButtons())->toBe(['bold', 'italic']);
});

it('can set toolbar buttons as grouped array', function () {
    $field = RichEditor::make('content')
        ->toolbarButtons([
            ['bold', 'italic'],
            ['h2', 'h3'],
            ['link'],
        ]);

    expect($field->hasGroupedToolbarButtons())->toBeTrue()
        ->and($field->getFlatToolbarButtons())->toBe(['bold', 'italic', 'h2', 'h3', 'link']);
});

it('can disable specific toolbar buttons', function () {
    $field = RichEditor::make('content')
        ->toolbarButtons([
            ['bold', 'italic', 'underline'],
            ['codeBlock', 'blockquote'],
        ])
        ->disableToolbarButtons(['codeBlock', 'blockquote']);

    $flatButtons = $field->getFlatToolbarButtons();

    expect($flatButtons)->not->toContain('codeBlock', 'blockquote')
        ->and($flatButtons)->toContain('bold', 'italic', 'underline');
});

it('can disable all toolbar buttons', function () {
    $field = RichEditor::make('content')
        ->disableAllToolbarButtons();

    expect($field->areAllToolbarButtonsDisabled())->toBeTrue()
        ->and($field->getToolbarButtons())->toBe([]);
});

it('can enable only specific toolbar buttons', function () {
    $field = RichEditor::make('content')
        ->toolbarButtons(['bold', 'italic', 'underline', 'link'])
        ->enableToolbarButtons(['bold', 'link']);

    $flatButtons = $field->getFlatToolbarButtons();

    expect($flatButtons)->toContain('bold', 'link')
        ->and($flatButtons)->not->toContain('italic', 'underline');
});

it('can set max length', function () {
    $field = RichEditor::make('content')->maxLength(5000);

    expect($field->getMaxLength())->toBe(5000);
});

it('has html output format by default', function () {
    $field = RichEditor::make('content');

    expect($field->getOutputFormat())->toBe('html');
});

it('can set output format', function () {
    $field = RichEditor::make('content')->output('json');

    expect($field->getOutputFormat())->toBe('json');
});

it('can set file attachments disk', function () {
    $field = RichEditor::make('content')
        ->fileAttachmentsDisk('public');

    expect($field->getFileAttachmentsDisk())->toBe('public');
});

it('can set file attachments directory', function () {
    $field = RichEditor::make('content')
        ->fileAttachmentsDirectory('uploads/articles');

    expect($field->getFileAttachmentsDirectory())->toBe('uploads/articles');
});

it('can set file attachments visibility', function () {
    $field = RichEditor::make('content')
        ->fileAttachmentsVisibility('private');

    expect($field->getFileAttachmentsVisibility())->toBe('private');
});

it('can configure all file attachment options together', function () {
    $field = RichEditor::make('content')
        ->fileAttachmentsDisk('s3')
        ->fileAttachmentsDirectory('uploads')
        ->fileAttachmentsVisibility('public');

    expect($field->getFileAttachmentsDisk())->toBe('s3')
        ->and($field->getFileAttachmentsDirectory())->toBe('uploads')
        ->and($field->getFileAttachmentsVisibility())->toBe('public');
});
