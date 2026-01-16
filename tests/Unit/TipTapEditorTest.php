<?php

declare(strict_types=1);

use Accelade\Forms\Components\TipTapEditor;

it('can create a tiptap editor', function () {
    $field = TipTapEditor::make('content');

    expect($field->getName())->toBe('content')
        ->and($field->getId())->toBe('content');
});

it('has default profile', function () {
    $field = TipTapEditor::make('content');

    expect($field->getProfile())->toBe('default');
});

it('can set profile', function () {
    $field = TipTapEditor::make('content')
        ->profile('simple');

    expect($field->getProfile())->toBe('simple');
});

it('has correct tools for each profile', function () {
    $default = TipTapEditor::make('content')->profile('default');
    $simple = TipTapEditor::make('content')->profile('simple');
    $minimal = TipTapEditor::make('content')->profile('minimal');
    $none = TipTapEditor::make('content')->profile('none');

    expect($default->getTools())->toContain('bold', 'italic', 'link')
        ->and($simple->getTools())->toContain('bold', 'italic')
        ->and($minimal->getTools())->toContain('bold', 'italic', 'link')
        ->and($none->getTools())->toBe([]);
});

it('can set custom tools', function () {
    $field = TipTapEditor::make('content')
        ->tools(['bold', 'italic', '|', 'link']);

    expect($field->getTools())->toBe(['bold', 'italic', '|', 'link']);
});

it('can group tools by separator', function () {
    $field = TipTapEditor::make('content')
        ->tools(['bold', 'italic', '|', 'h2', 'h3', '|', 'link']);

    $groups = $field->getGroupedTools();

    expect($groups)->toHaveCount(3)
        ->and($groups[0])->toBe(['bold', 'italic'])
        ->and($groups[1])->toBe(['h2', 'h3'])
        ->and($groups[2])->toBe(['link']);
});

it('has html output format by default', function () {
    $field = TipTapEditor::make('content');

    expect($field->getOutputFormat())->toBe('html');
});

it('can set output format', function () {
    $field = TipTapEditor::make('content')->output('json');

    expect($field->getOutputFormat())->toBe('json');
});

it('can set max length', function () {
    $field = TipTapEditor::make('content')->maxLength(500);

    expect($field->getMaxLength())->toBe(500);
});

it('can show character count', function () {
    $field = TipTapEditor::make('content')->showCharacterCount();

    expect($field->shouldShowCharacterCount())->toBeTrue();
});

it('shows character count automatically when max length is set', function () {
    $field = TipTapEditor::make('content')->maxLength(1000);

    expect($field->shouldShowCharacterCount())->toBeTrue();
});

it('can disable floating menus', function () {
    $field = TipTapEditor::make('content')->disableFloatingMenus();

    expect($field->hasFloatingMenus())->toBeFalse();
});

it('can disable bubble menus', function () {
    $field = TipTapEditor::make('content')->disableBubbleMenus();

    expect($field->hasBubbleMenus())->toBeFalse();
});

it('can set floating menu tools', function () {
    $field = TipTapEditor::make('content')
        ->floatingMenuTools(['media', 'table']);

    expect($field->getFloatingMenuTools())->toBe(['media', 'table']);
});

it('can set storage disk', function () {
    $field = TipTapEditor::make('content')
        ->disk('s3');

    expect($field->getDisk())->toBe('s3');
});

it('can set storage directory', function () {
    $field = TipTapEditor::make('content')
        ->directory('uploads/articles');

    expect($field->getDirectory())->toBe('uploads/articles');
});

it('can set file visibility', function () {
    $field = TipTapEditor::make('content')
        ->visibility('private');

    expect($field->getVisibility())->toBe('private');
});

it('can set max file size', function () {
    $field = TipTapEditor::make('content')
        ->maxSize(10240);

    expect($field->getMaxSize())->toBe(10240);
});

it('can set accepted file types', function () {
    $field = TipTapEditor::make('content')
        ->acceptedFileTypes(['image/png', 'image/jpeg']);

    expect($field->getAcceptedFileTypes())->toBe(['image/png', 'image/jpeg']);
});

it('can preserve filenames', function () {
    $field = TipTapEditor::make('content')
        ->preserveFilenames();

    expect($field->shouldPreserveFilenames())->toBeTrue();
});

it('can set image crop aspect ratio', function () {
    $field = TipTapEditor::make('content')
        ->imageCropAspectRatio('16:9');

    expect($field->getImageCropAspectRatio())->toBe('16:9');
});

it('can set image resize dimensions', function () {
    $field = TipTapEditor::make('content')
        ->imageResizeTargetWidth(1200)
        ->imageResizeTargetHeight(675);

    expect($field->getImageResizeTargetWidth())->toBe(1200)
        ->and($field->getImageResizeTargetHeight())->toBe(675);
});

it('can set rtl direction', function () {
    $field = TipTapEditor::make('content')->rtl();

    expect($field->getDirection())->toBe('rtl');
});

it('can set direction explicitly', function () {
    $field = TipTapEditor::make('content')->direction('rtl');

    expect($field->getDirection())->toBe('rtl');
});

it('can set merge tags', function () {
    $field = TipTapEditor::make('content')
        ->mergeTags(['first_name', 'last_name']);

    expect($field->getMergeTags())->toBe(['first_name', 'last_name']);
});

it('can set grid layouts', function () {
    $field = TipTapEditor::make('content')
        ->gridLayouts(['two-columns', 'three-columns']);

    expect($field->getGridLayouts())->toBe(['two-columns', 'three-columns']);
});

it('can set preset colors', function () {
    $field = TipTapEditor::make('content')
        ->presetColors(['primary' => '#3B82F6']);

    expect($field->getPresetColors())->toBe(['primary' => '#3B82F6']);
});

it('can set extra input attributes', function () {
    $field = TipTapEditor::make('content')
        ->extraInputAttributes(['style' => 'min-height: 20rem;']);

    expect($field->getExtraInputAttributes())->toBe(['style' => 'min-height: 20rem;']);
});

it('can enable collaboration', function () {
    $field = TipTapEditor::make('content')->collaboration();

    expect($field->hasCollaboration())->toBeTrue();
});

it('returns complete config for javascript', function () {
    $field = TipTapEditor::make('content')
        ->profile('simple')
        ->maxLength(500)
        ->rtl();

    $config = $field->getConfig();

    expect($config)->toBeArray()
        ->and($config['profile'])->toBe('simple')
        ->and($config['maxLength'])->toBe(500)
        ->and($config['direction'])->toBe('rtl');
});
