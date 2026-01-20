<?php

declare(strict_types=1);

namespace Accelade\Forms\Components;

use Accelade\Forms\Field;

/**
 * Code editor field component with syntax highlighting.
 *
 * Uses CodeMirror for syntax highlighting with support for multiple languages.
 */
class CodeEditor extends Field
{
    protected string $language = 'javascript';

    protected string $theme = 'light';

    protected bool $lineNumbers = true;

    protected int $minHeight = 300;

    protected ?int $maxHeight = null;

    protected bool $lineWrapping = false;

    protected int $tabSize = 4;

    protected bool $indentWithTabs = false;

    protected bool $highlightActiveLine = true;

    protected bool $bracketMatching = true;

    protected bool $autoCloseBrackets = true;

    protected bool $foldGutter = false;

    /**
     * Supported languages mapping to CodeMirror language modules.
     *
     * @var array<string, string>
     */
    public const LANGUAGES = [
        'cpp' => 'C++',
        'c++' => 'C++',
        'css' => 'CSS',
        'html' => 'HTML',
        'java' => 'Java',
        'javascript' => 'JavaScript',
        'js' => 'JavaScript',
        'jsx' => 'JSX',
        'typescript' => 'TypeScript',
        'ts' => 'TypeScript',
        'tsx' => 'TSX',
        'json' => 'JSON',
        'markdown' => 'Markdown',
        'md' => 'Markdown',
        'php' => 'PHP',
        'python' => 'Python',
        'py' => 'Python',
        'sql' => 'SQL',
        'xml' => 'XML',
        'yaml' => 'YAML',
        'yml' => 'YAML',
        'bash' => 'Bash',
        'shell' => 'Shell',
        'rust' => 'Rust',
        'go' => 'Go',
    ];

    /**
     * Set the programming language for syntax highlighting.
     */
    public function language(string $language): static
    {
        $this->language = strtolower($language);

        return $this;
    }

    /**
     * Get the language.
     */
    public function getLanguage(): string
    {
        return $this->language;
    }

    /**
     * Get the human-readable language label.
     */
    public function getLanguageLabel(): string
    {
        return self::LANGUAGES[$this->language] ?? strtoupper($this->language);
    }

    /**
     * Set the editor theme.
     */
    public function theme(string $theme): static
    {
        $this->theme = $theme;

        return $this;
    }

    /**
     * Use the dark theme.
     */
    public function dark(): static
    {
        return $this->theme('dark');
    }

    /**
     * Use the light theme.
     */
    public function light(): static
    {
        return $this->theme('light');
    }

    /**
     * Get the theme.
     */
    public function getTheme(): string
    {
        return $this->theme;
    }

    /**
     * Enable or disable line numbers.
     */
    public function lineNumbers(bool $show = true): static
    {
        $this->lineNumbers = $show;

        return $this;
    }

    /**
     * Check if line numbers are enabled.
     */
    public function hasLineNumbers(): bool
    {
        return $this->lineNumbers;
    }

    /**
     * Set the minimum height of the editor in pixels.
     */
    public function minHeight(int $height): static
    {
        $this->minHeight = $height;

        return $this;
    }

    /**
     * Get the minimum height.
     */
    public function getMinHeight(): int
    {
        return $this->minHeight;
    }

    /**
     * Set the maximum height of the editor in pixels.
     */
    public function maxHeight(?int $height): static
    {
        $this->maxHeight = $height;

        return $this;
    }

    /**
     * Get the maximum height.
     */
    public function getMaxHeight(): ?int
    {
        return $this->maxHeight;
    }

    /**
     * Enable or disable line wrapping.
     */
    public function lineWrapping(bool $wrap = true): static
    {
        $this->lineWrapping = $wrap;

        return $this;
    }

    /**
     * Check if line wrapping is enabled.
     */
    public function hasLineWrapping(): bool
    {
        return $this->lineWrapping;
    }

    /**
     * Set the tab size (number of spaces).
     */
    public function tabSize(int $size): static
    {
        $this->tabSize = $size;

        return $this;
    }

    /**
     * Get the tab size.
     */
    public function getTabSize(): int
    {
        return $this->tabSize;
    }

    /**
     * Use tabs instead of spaces for indentation.
     */
    public function indentWithTabs(bool $useTabs = true): static
    {
        $this->indentWithTabs = $useTabs;

        return $this;
    }

    /**
     * Check if indenting with tabs.
     */
    public function isIndentingWithTabs(): bool
    {
        return $this->indentWithTabs;
    }

    /**
     * Enable or disable active line highlighting.
     */
    public function highlightActiveLine(bool $highlight = true): static
    {
        $this->highlightActiveLine = $highlight;

        return $this;
    }

    /**
     * Check if active line highlighting is enabled.
     */
    public function hasHighlightActiveLine(): bool
    {
        return $this->highlightActiveLine;
    }

    /**
     * Enable or disable bracket matching.
     */
    public function bracketMatching(bool $match = true): static
    {
        $this->bracketMatching = $match;

        return $this;
    }

    /**
     * Check if bracket matching is enabled.
     */
    public function hasBracketMatching(): bool
    {
        return $this->bracketMatching;
    }

    /**
     * Enable or disable auto-closing brackets.
     */
    public function autoCloseBrackets(bool $autoClose = true): static
    {
        $this->autoCloseBrackets = $autoClose;

        return $this;
    }

    /**
     * Check if auto-closing brackets is enabled.
     */
    public function hasAutoCloseBrackets(): bool
    {
        return $this->autoCloseBrackets;
    }

    /**
     * Enable or disable code folding gutter.
     */
    public function foldGutter(bool $show = true): static
    {
        $this->foldGutter = $show;

        return $this;
    }

    /**
     * Check if fold gutter is enabled.
     */
    public function hasFoldGutter(): bool
    {
        return $this->foldGutter;
    }

    /**
     * Shorthand for JavaScript language.
     */
    public function javascript(): static
    {
        return $this->language('javascript');
    }

    /**
     * Shorthand for TypeScript language.
     */
    public function typescript(): static
    {
        return $this->language('typescript');
    }

    /**
     * Shorthand for PHP language.
     */
    public function php(): static
    {
        return $this->language('php');
    }

    /**
     * Shorthand for HTML language.
     */
    public function html(): static
    {
        return $this->language('html');
    }

    /**
     * Shorthand for CSS language.
     */
    public function css(): static
    {
        return $this->language('css');
    }

    /**
     * Shorthand for JSON language.
     */
    public function json(): static
    {
        return $this->language('json');
    }

    /**
     * Shorthand for SQL language.
     */
    public function sql(): static
    {
        return $this->language('sql');
    }

    /**
     * Shorthand for Python language.
     */
    public function python(): static
    {
        return $this->language('python');
    }

    /**
     * Shorthand for Markdown language.
     */
    public function markdown(): static
    {
        return $this->language('markdown');
    }

    /**
     * Shorthand for YAML language.
     */
    public function yaml(): static
    {
        return $this->language('yaml');
    }

    /**
     * Get the configuration array for JavaScript.
     *
     * @return array<string, mixed>
     */
    public function getConfig(): array
    {
        return [
            'language' => $this->language,
            'theme' => $this->theme,
            'lineNumbers' => $this->lineNumbers,
            'minHeight' => $this->minHeight,
            'maxHeight' => $this->maxHeight,
            'lineWrapping' => $this->lineWrapping,
            'tabSize' => $this->tabSize,
            'indentWithTabs' => $this->indentWithTabs,
            'highlightActiveLine' => $this->highlightActiveLine,
            'bracketMatching' => $this->bracketMatching,
            'autoCloseBrackets' => $this->autoCloseBrackets,
            'foldGutter' => $this->foldGutter,
            'readOnly' => $this->isReadonly() || $this->isDisabled(),
            'placeholder' => $this->getPlaceholder(),
        ];
    }

    /**
     * Get the view name.
     */
    protected function getView(): string
    {
        return 'forms::components.code-editor';
    }
}
