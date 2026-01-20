{{-- Code Editor Component Section --}}
@props(['prefix' => 'a'])

@php
    use Accelade\Forms\Components\CodeEditor;

    // JavaScript editor with dark theme
    $jsEditor = CodeEditor::make('javascript_code')
        ->label('JavaScript Editor')
        ->javascript()
        ->dark()
        ->minHeight(250)
        ->default("function greet(name) {\n    console.log(`Hello, \${name}!`);\n}\n\ngreet('World');")
        ->helperText('Full-featured JavaScript editor with dark theme');

    // TypeScript editor with light theme
    $tsEditor = CodeEditor::make('typescript_code')
        ->label('TypeScript Editor')
        ->typescript()
        ->light()
        ->minHeight(250)
        ->default("interface User {\n    id: number;\n    name: string;\n    email: string;\n}\n\nconst user: User = {\n    id: 1,\n    name: 'John Doe',\n    email: 'john@example.com'\n};")
        ->helperText('TypeScript with type annotations');

    // PHP editor
    $phpEditor = CodeEditor::make('php_code')
        ->label('PHP Editor')
        ->php()
        ->minHeight(250)
        ->default("<?php\n\nnamespace App\\Models;\n\nclass User extends Model\n{\n    protected \$fillable = [\n        'name',\n        'email',\n        'password',\n    ];\n}")
        ->helperText('PHP code with Laravel model example');

    // SQL editor
    $sqlEditor = CodeEditor::make('sql_query')
        ->label('SQL Query Editor')
        ->sql()
        ->minHeight(200)
        ->default("SELECT \n    users.id,\n    users.name,\n    users.email,\n    COUNT(orders.id) as order_count\nFROM users\nLEFT JOIN orders ON users.id = orders.user_id\nWHERE users.active = 1\nGROUP BY users.id\nORDER BY order_count DESC\nLIMIT 10;")
        ->helperText('SQL syntax highlighting');

    // JSON editor
    $jsonEditor = CodeEditor::make('json_config')
        ->label('JSON Configuration')
        ->json()
        ->minHeight(200)
        ->lineWrapping()
        ->default("{\n    \"name\": \"accelade-forms\",\n    \"version\": \"1.0.0\",\n    \"dependencies\": {\n        \"codemirror\": \"^6.0.0\"\n    },\n    \"config\": {\n        \"editor\": {\n            \"theme\": \"dark\",\n            \"lineNumbers\": true\n        }\n    }\n}")
        ->helperText('JSON with line wrapping enabled');

    // Python editor
    $pythonEditor = CodeEditor::make('python_code')
        ->label('Python Editor')
        ->python()
        ->dark()
        ->minHeight(200)
        ->default("def fibonacci(n: int) -> list[int]:\n    \"\"\"Generate Fibonacci sequence up to n terms.\"\"\"\n    if n <= 0:\n        return []\n    elif n == 1:\n        return [0]\n    \n    sequence = [0, 1]\n    while len(sequence) < n:\n        sequence.append(sequence[-1] + sequence[-2])\n    \n    return sequence\n\nprint(fibonacci(10))")
        ->helperText('Python with type hints');

    // HTML editor
    $htmlEditor = CodeEditor::make('html_code')
        ->label('HTML Editor')
        ->html()
        ->minHeight(200)
        ->default("<!DOCTYPE html>\n<html lang=\"en\">\n<head>\n    <meta charset=\"UTF-8\">\n    <title>Hello World</title>\n</head>\n<body>\n    <h1>Welcome</h1>\n    <p>This is a sample HTML document.</p>\n</body>\n</html>")
        ->helperText('HTML structure highlighting');

    // CSS editor
    $cssEditor = CodeEditor::make('css_code')
        ->label('CSS Editor')
        ->css()
        ->minHeight(200)
        ->default(".container {\n    display: flex;\n    justify-content: center;\n    align-items: center;\n    min-height: 100vh;\n    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);\n}\n\n.card {\n    padding: 2rem;\n    border-radius: 1rem;\n    background: white;\n    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);\n}")
        ->helperText('CSS with modern features');

    // YAML editor
    $yamlEditor = CodeEditor::make('yaml_config')
        ->label('YAML Configuration')
        ->yaml()
        ->minHeight(200)
        ->default("app:\n  name: Accelade\n  version: 1.0.0\n  debug: true\n\ndatabase:\n  driver: mysql\n  host: localhost\n  port: 3306\n  name: accelade\n  username: root\n  password: secret\n\ncache:\n  driver: redis\n  ttl: 3600")
        ->helperText('YAML configuration file');

    // Readonly editor
    $readonlyEditor = CodeEditor::make('readonly_code')
        ->label('Readonly Code')
        ->javascript()
        ->readonly()
        ->minHeight(150)
        ->default("// This code cannot be edited\nconst VERSION = '1.0.0';\nconst API_URL = 'https://api.example.com';")
        ->helperText('Editor is in readonly mode');

    // Editor with code folding
    $foldableEditor = CodeEditor::make('foldable_code')
        ->label('Code with Folding')
        ->javascript()
        ->foldGutter()
        ->minHeight(300)
        ->default("class Calculator {\n    constructor() {\n        this.result = 0;\n    }\n\n    add(num) {\n        this.result += num;\n        return this;\n    }\n\n    subtract(num) {\n        this.result -= num;\n        return this;\n    }\n\n    multiply(num) {\n        this.result *= num;\n        return this;\n    }\n\n    divide(num) {\n        if (num === 0) {\n            throw new Error('Cannot divide by zero');\n        }\n        this.result /= num;\n        return this;\n    }\n\n    getResult() {\n        return this.result;\n    }\n\n    reset() {\n        this.result = 0;\n        return this;\n    }\n}")
        ->helperText('Click the arrows in the gutter to fold/unfold code blocks');
@endphp

<section class="rounded-xl p-6 mb-6 border border-[var(--docs-border)]" style="background: var(--docs-bg-alt);">
    <div class="flex items-center gap-3 mb-2">
        <span class="w-2.5 h-2.5 bg-violet-500 rounded-full"></span>
        <h3 class="text-lg font-semibold" style="color: var(--docs-text);">Code Editor</h3>
    </div>
    <p class="text-sm mb-6" style="color: var(--docs-text-muted);">
        Syntax-highlighted code editor powered by CodeMirror. Supports multiple languages, themes, and editor features like code folding and bracket matching.
    </p>

    <div class="space-y-6 mb-6">
        {{-- JavaScript Editor (Dark) --}}
        <div class="rounded-xl p-4 border border-violet-500/30" style="background: rgba(139, 92, 246, 0.1);">
            <h4 class="font-medium mb-3 flex items-center gap-2" style="color: var(--docs-text);">
                <span class="text-xs px-2 py-1 bg-violet-500/20 text-violet-500 rounded">JS</span>
                JavaScript (Dark Theme)
            </h4>
            {!! $jsEditor !!}
        </div>

        {{-- TypeScript Editor (Light) --}}
        <div class="rounded-xl p-4 border border-blue-500/30" style="background: rgba(59, 130, 246, 0.1);">
            <h4 class="font-medium mb-3 flex items-center gap-2" style="color: var(--docs-text);">
                <span class="text-xs px-2 py-1 bg-blue-500/20 text-blue-500 rounded">TS</span>
                TypeScript (Light Theme)
            </h4>
            {!! $tsEditor !!}
        </div>

        {{-- PHP Editor --}}
        <div class="rounded-xl p-4 border border-indigo-500/30" style="background: rgba(99, 102, 241, 0.1);">
            <h4 class="font-medium mb-3 flex items-center gap-2" style="color: var(--docs-text);">
                <span class="text-xs px-2 py-1 bg-indigo-500/20 text-indigo-500 rounded">PHP</span>
                PHP
            </h4>
            {!! $phpEditor !!}
        </div>

        {{-- SQL Editor --}}
        <div class="rounded-xl p-4 border border-emerald-500/30" style="background: rgba(16, 185, 129, 0.1);">
            <h4 class="font-medium mb-3 flex items-center gap-2" style="color: var(--docs-text);">
                <span class="text-xs px-2 py-1 bg-emerald-500/20 text-emerald-500 rounded">SQL</span>
                SQL Query
            </h4>
            {!! $sqlEditor !!}
        </div>

        {{-- JSON Editor --}}
        <div class="rounded-xl p-4 border border-amber-500/30" style="background: rgba(245, 158, 11, 0.1);">
            <h4 class="font-medium mb-3 flex items-center gap-2" style="color: var(--docs-text);">
                <span class="text-xs px-2 py-1 bg-amber-500/20 text-amber-500 rounded">JSON</span>
                JSON Configuration
            </h4>
            {!! $jsonEditor !!}
        </div>

        {{-- Python Editor --}}
        <div class="rounded-xl p-4 border border-yellow-500/30" style="background: rgba(234, 179, 8, 0.1);">
            <h4 class="font-medium mb-3 flex items-center gap-2" style="color: var(--docs-text);">
                <span class="text-xs px-2 py-1 bg-yellow-500/20 text-yellow-600 rounded">PY</span>
                Python (Dark Theme)
            </h4>
            {!! $pythonEditor !!}
        </div>

        {{-- HTML Editor --}}
        <div class="rounded-xl p-4 border border-orange-500/30" style="background: rgba(249, 115, 22, 0.1);">
            <h4 class="font-medium mb-3 flex items-center gap-2" style="color: var(--docs-text);">
                <span class="text-xs px-2 py-1 bg-orange-500/20 text-orange-500 rounded">HTML</span>
                HTML
            </h4>
            {!! $htmlEditor !!}
        </div>

        {{-- CSS Editor --}}
        <div class="rounded-xl p-4 border border-pink-500/30" style="background: rgba(236, 72, 153, 0.1);">
            <h4 class="font-medium mb-3 flex items-center gap-2" style="color: var(--docs-text);">
                <span class="text-xs px-2 py-1 bg-pink-500/20 text-pink-500 rounded">CSS</span>
                CSS
            </h4>
            {!! $cssEditor !!}
        </div>

        {{-- YAML Editor --}}
        <div class="rounded-xl p-4 border border-rose-500/30" style="background: rgba(244, 63, 94, 0.1);">
            <h4 class="font-medium mb-3 flex items-center gap-2" style="color: var(--docs-text);">
                <span class="text-xs px-2 py-1 bg-rose-500/20 text-rose-500 rounded">YAML</span>
                YAML Configuration
            </h4>
            {!! $yamlEditor !!}
        </div>

        {{-- Readonly Editor --}}
        <div class="rounded-xl p-4 border border-gray-500/30" style="background: rgba(107, 114, 128, 0.1);">
            <h4 class="font-medium mb-3 flex items-center gap-2" style="color: var(--docs-text);">
                <span class="text-xs px-2 py-1 bg-gray-500/20 text-gray-500 rounded">RO</span>
                Readonly Mode
            </h4>
            {!! $readonlyEditor !!}
        </div>

        {{-- Code Folding Editor --}}
        <div class="rounded-xl p-4 border border-cyan-500/30" style="background: rgba(6, 182, 212, 0.1);">
            <h4 class="font-medium mb-3 flex items-center gap-2" style="color: var(--docs-text);">
                <span class="text-xs px-2 py-1 bg-cyan-500/20 text-cyan-500 rounded">Fold</span>
                Code Folding
            </h4>
            {!! $foldableEditor !!}
        </div>
    </div>

    {{-- API Reference --}}
    <div class="mt-8">
        <h4 class="text-md font-semibold mb-4" style="color: var(--docs-text);">API Reference</h4>

        <x-accelade::code-block language="php" filename="code-editor-examples.php">
use Accelade\Forms\Components\CodeEditor;

// Basic JavaScript editor
CodeEditor::make('code')
    ->label('Code')
    ->javascript();

// TypeScript with dark theme
CodeEditor::make('script')
    ->label('Script')
    ->typescript()
    ->dark()
    ->minHeight(400);

// PHP editor with code folding
CodeEditor::make('source')
    ->label('Source Code')
    ->php()
    ->foldGutter()
    ->lineWrapping();

// SQL query editor
CodeEditor::make('query')
    ->label('SQL Query')
    ->sql()
    ->placeholder('SELECT * FROM users...');

// JSON configuration
CodeEditor::make('config')
    ->label('Configuration')
    ->json()
    ->lineWrapping();

// Readonly code display
CodeEditor::make('output')
    ->label('Generated Code')
    ->readonly();

// Full configuration
CodeEditor::make('advanced')
    ->label('Advanced Editor')
    ->language('typescript')
    ->dark()
    ->lineNumbers()
    ->minHeight(300)
    ->maxHeight(600)
    ->tabSize(2)
    ->lineWrapping()
    ->highlightActiveLine()
    ->bracketMatching()
    ->autoCloseBrackets()
    ->foldGutter();
        </x-accelade::code-block>
    </div>

    {{-- Supported Languages Reference --}}
    <div class="mt-8">
        <h4 class="text-md font-semibold mb-4" style="color: var(--docs-text);">Supported Languages</h4>

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
            @foreach([
                ['JavaScript', 'javascript, js'],
                ['TypeScript', 'typescript, ts'],
                ['JSX', 'jsx'],
                ['TSX', 'tsx'],
                ['PHP', 'php'],
                ['HTML', 'html'],
                ['CSS', 'css'],
                ['JSON', 'json'],
                ['SQL', 'sql'],
                ['Python', 'python, py'],
                ['Markdown', 'markdown, md'],
                ['YAML', 'yaml, yml'],
                ['XML', 'xml'],
                ['Java', 'java'],
                ['C++', 'cpp, c++'],
                ['Rust', 'rust'],
                ['Go', 'go'],
                ['Bash', 'bash, shell'],
            ] as $lang)
                <div class="rounded-lg p-2.5" style="background: var(--docs-bg);">
                    <span class="font-medium text-sm" style="color: var(--docs-text);">{{ $lang[0] }}</span>
                    <span class="text-xs block" style="color: var(--docs-text-muted);">{{ $lang[1] }}</span>
                </div>
            @endforeach
        </div>
    </div>
</section>
