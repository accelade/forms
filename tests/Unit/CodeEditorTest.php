<?php

declare(strict_types=1);

use Accelade\Forms\Components\CodeEditor;

it('can create a code editor', function () {
    $field = CodeEditor::make('code');

    expect($field->getName())->toBe('code')
        ->and($field->getId())->toBe('code');
});

it('has javascript as default language', function () {
    $field = CodeEditor::make('code');

    expect($field->getLanguage())->toBe('javascript');
});

it('can set language', function () {
    $field = CodeEditor::make('code')->language('typescript');

    expect($field->getLanguage())->toBe('typescript');
});

it('normalizes language to lowercase', function () {
    $field = CodeEditor::make('code')->language('PHP');

    expect($field->getLanguage())->toBe('php');
});

it('can get human-readable language label', function () {
    expect(CodeEditor::make('code')->language('js')->getLanguageLabel())->toBe('JavaScript')
        ->and(CodeEditor::make('code')->language('ts')->getLanguageLabel())->toBe('TypeScript')
        ->and(CodeEditor::make('code')->language('php')->getLanguageLabel())->toBe('PHP')
        ->and(CodeEditor::make('code')->language('unknown')->getLanguageLabel())->toBe('UNKNOWN');
});

it('has light theme by default', function () {
    $field = CodeEditor::make('code');

    expect($field->getTheme())->toBe('light');
});

it('can set dark theme', function () {
    $field = CodeEditor::make('code')->dark();

    expect($field->getTheme())->toBe('dark');
});

it('can set light theme', function () {
    $field = CodeEditor::make('code')->dark()->light();

    expect($field->getTheme())->toBe('light');
});

it('can set theme directly', function () {
    $field = CodeEditor::make('code')->theme('dark');

    expect($field->getTheme())->toBe('dark');
});

it('has line numbers enabled by default', function () {
    $field = CodeEditor::make('code');

    expect($field->hasLineNumbers())->toBeTrue();
});

it('can disable line numbers', function () {
    $field = CodeEditor::make('code')->lineNumbers(false);

    expect($field->hasLineNumbers())->toBeFalse();
});

it('has default min height of 300', function () {
    $field = CodeEditor::make('code');

    expect($field->getMinHeight())->toBe(300);
});

it('can set min height', function () {
    $field = CodeEditor::make('code')->minHeight(500);

    expect($field->getMinHeight())->toBe(500);
});

it('has no max height by default', function () {
    $field = CodeEditor::make('code');

    expect($field->getMaxHeight())->toBeNull();
});

it('can set max height', function () {
    $field = CodeEditor::make('code')->maxHeight(800);

    expect($field->getMaxHeight())->toBe(800);
});

it('does not have line wrapping by default', function () {
    $field = CodeEditor::make('code');

    expect($field->hasLineWrapping())->toBeFalse();
});

it('can enable line wrapping', function () {
    $field = CodeEditor::make('code')->lineWrapping();

    expect($field->hasLineWrapping())->toBeTrue();
});

it('has default tab size of 4', function () {
    $field = CodeEditor::make('code');

    expect($field->getTabSize())->toBe(4);
});

it('can set tab size', function () {
    $field = CodeEditor::make('code')->tabSize(2);

    expect($field->getTabSize())->toBe(2);
});

it('does not indent with tabs by default', function () {
    $field = CodeEditor::make('code');

    expect($field->isIndentingWithTabs())->toBeFalse();
});

it('can enable indent with tabs', function () {
    $field = CodeEditor::make('code')->indentWithTabs();

    expect($field->isIndentingWithTabs())->toBeTrue();
});

it('has active line highlighting enabled by default', function () {
    $field = CodeEditor::make('code');

    expect($field->hasHighlightActiveLine())->toBeTrue();
});

it('can disable active line highlighting', function () {
    $field = CodeEditor::make('code')->highlightActiveLine(false);

    expect($field->hasHighlightActiveLine())->toBeFalse();
});

it('has bracket matching enabled by default', function () {
    $field = CodeEditor::make('code');

    expect($field->hasBracketMatching())->toBeTrue();
});

it('can disable bracket matching', function () {
    $field = CodeEditor::make('code')->bracketMatching(false);

    expect($field->hasBracketMatching())->toBeFalse();
});

it('has auto close brackets enabled by default', function () {
    $field = CodeEditor::make('code');

    expect($field->hasAutoCloseBrackets())->toBeTrue();
});

it('can disable auto close brackets', function () {
    $field = CodeEditor::make('code')->autoCloseBrackets(false);

    expect($field->hasAutoCloseBrackets())->toBeFalse();
});

it('does not have fold gutter by default', function () {
    $field = CodeEditor::make('code');

    expect($field->hasFoldGutter())->toBeFalse();
});

it('can enable fold gutter', function () {
    $field = CodeEditor::make('code')->foldGutter();

    expect($field->hasFoldGutter())->toBeTrue();
});

// Language shorthand methods
it('can use javascript shorthand', function () {
    $field = CodeEditor::make('code')->javascript();

    expect($field->getLanguage())->toBe('javascript');
});

it('can use typescript shorthand', function () {
    $field = CodeEditor::make('code')->typescript();

    expect($field->getLanguage())->toBe('typescript');
});

it('can use php shorthand', function () {
    $field = CodeEditor::make('code')->php();

    expect($field->getLanguage())->toBe('php');
});

it('can use html shorthand', function () {
    $field = CodeEditor::make('code')->html();

    expect($field->getLanguage())->toBe('html');
});

it('can use css shorthand', function () {
    $field = CodeEditor::make('code')->css();

    expect($field->getLanguage())->toBe('css');
});

it('can use json shorthand', function () {
    $field = CodeEditor::make('code')->json();

    expect($field->getLanguage())->toBe('json');
});

it('can use sql shorthand', function () {
    $field = CodeEditor::make('code')->sql();

    expect($field->getLanguage())->toBe('sql');
});

it('can use python shorthand', function () {
    $field = CodeEditor::make('code')->python();

    expect($field->getLanguage())->toBe('python');
});

it('can use markdown shorthand', function () {
    $field = CodeEditor::make('code')->markdown();

    expect($field->getLanguage())->toBe('markdown');
});

it('can use yaml shorthand', function () {
    $field = CodeEditor::make('code')->yaml();

    expect($field->getLanguage())->toBe('yaml');
});

it('can get configuration array', function () {
    $field = CodeEditor::make('code')
        ->language('typescript')
        ->dark()
        ->minHeight(400)
        ->maxHeight(800)
        ->tabSize(2)
        ->lineWrapping()
        ->foldGutter()
        ->placeholder('Enter code...');

    $config = $field->getConfig();

    expect($config['language'])->toBe('typescript')
        ->and($config['theme'])->toBe('dark')
        ->and($config['lineNumbers'])->toBeTrue()
        ->and($config['minHeight'])->toBe(400)
        ->and($config['maxHeight'])->toBe(800)
        ->and($config['tabSize'])->toBe(2)
        ->and($config['lineWrapping'])->toBeTrue()
        ->and($config['foldGutter'])->toBeTrue()
        ->and($config['placeholder'])->toBe('Enter code...');
});

it('includes readonly state in config', function () {
    $field = CodeEditor::make('code')->readonly();

    $config = $field->getConfig();

    expect($config['readOnly'])->toBeTrue();
});

it('includes disabled state in config as readonly', function () {
    $field = CodeEditor::make('code')->disabled();

    $config = $field->getConfig();

    expect($config['readOnly'])->toBeTrue();
});

it('can chain multiple configurations', function () {
    $field = CodeEditor::make('code')
        ->label('Source Code')
        ->typescript()
        ->dark()
        ->minHeight(300)
        ->maxHeight(600)
        ->tabSize(2)
        ->lineWrapping()
        ->foldGutter()
        ->required()
        ->placeholder('Enter TypeScript code...')
        ->helperText('Write your code here');

    expect($field->getLabel())->toBe('Source Code')
        ->and($field->getLanguage())->toBe('typescript')
        ->and($field->getTheme())->toBe('dark')
        ->and($field->getMinHeight())->toBe(300)
        ->and($field->getMaxHeight())->toBe(600)
        ->and($field->getTabSize())->toBe(2)
        ->and($field->hasLineWrapping())->toBeTrue()
        ->and($field->hasFoldGutter())->toBeTrue()
        ->and($field->isRequired())->toBeTrue()
        ->and($field->getPlaceholder())->toBe('Enter TypeScript code...')
        ->and($field->getHelperText())->toBe('Write your code here');
});
