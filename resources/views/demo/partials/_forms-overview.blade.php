{{-- Forms Overview Section --}}
@props(['prefix' => 'a'])

@php
    use Accelade\Forms\Components\TextInput;
    use Accelade\Forms\Components\Textarea;
    use Accelade\Forms\Components\Select;
    use Accelade\Forms\Components\Checkbox;
    use Accelade\Forms\Components\CheckboxList;
    use Accelade\Forms\Components\Radio;
    use Accelade\Forms\Components\Toggle;
    use Accelade\Forms\Components\NumberField;
    use Accelade\Forms\Components\Slider;
    use Accelade\Forms\Components\DatePicker;
    use Accelade\Forms\Components\TimePicker;
    use Accelade\Forms\Components\ColorPicker;
    use Accelade\Forms\Components\FileUpload;
    use Accelade\Forms\Components\TagsInput;
    use Accelade\Forms\Components\KeyValue;
    use Accelade\Forms\Components\Repeater;
    use Accelade\Forms\Components\RichEditor;
    use Accelade\Forms\Components\MarkdownEditor;
    use Accelade\Forms\Components\PinInput;
    use Accelade\Forms\Components\RateInput;
    use Accelade\Forms\Components\IconPicker;

    // Text Inputs
    $nameInput = TextInput::make('name')
        ->label('Full Name')
        ->placeholder('Enter your full name')
        ->required();

    $emailInput = TextInput::make('email')
        ->label('Email Address')
        ->placeholder('your@email.com')
        ->required();

    $passwordInput = TextInput::make('password')
        ->label('Password')
        ->password()
        ->placeholder('Enter password');

    // Textarea
    $bioTextarea = Textarea::make('bio')
        ->label('Biography')
        ->placeholder('Tell us about yourself...')
        ->rows(3);

    // Select
    $countrySelect = Select::make('country')
        ->label('Country')
        ->options([
            'us' => 'United States',
            'uk' => 'United Kingdom',
            'ca' => 'Canada',
            'au' => 'Australia',
            'de' => 'Germany',
            'fr' => 'France',
        ])
        ->placeholder('Select a country...')
        ->searchable();

    $rolesSelect = Select::make('roles')
        ->label('Roles')
        ->options([
            'admin' => 'Administrator',
            'editor' => 'Editor',
            'viewer' => 'Viewer',
        ])
        ->multiple()
        ->default(['viewer']);

    // Checkbox
    $termsCheckbox = Checkbox::make('terms')
        ->label('I agree to the terms and conditions')
        ->required();

    $interestsCheckboxList = CheckboxList::make('interests')
        ->label('Interests')
        ->options([
            'tech' => 'Technology',
            'sports' => 'Sports',
            'music' => 'Music',
            'travel' => 'Travel',
        ])
        ->columns(2);

    // Radio
    $genderRadio = Radio::make('gender')
        ->label('Gender')
        ->options([
            'male' => 'Male',
            'female' => 'Female',
            'other' => 'Other',
        ]);

    // Toggle
    $notificationsToggle = Toggle::make('notifications')
        ->label('Enable Notifications')
        ->default(true);

    // Number Field
    $ageField = NumberField::make('age')
        ->label('Age')
        ->minValue(18)
        ->maxValue(120)
        ->default(25);

    $priceField = NumberField::make('price')
        ->label('Price')
        ->prefix('$')
        ->suffix('USD')
        ->minValue(0)
        ->step(0.01)
        ->default(99.99);

    // Slider
    $volumeSlider = Slider::make('volume')
        ->label('Volume')
        ->minValue(0)
        ->maxValue(100)
        ->default(50);

    // Date Picker
    $birthdatePicker = DatePicker::make('birthdate')
        ->label('Birth Date')
        ->placeholder('Select date...');

    // Time Picker
    $meetingTimePicker = TimePicker::make('meeting_time')
        ->label('Meeting Time')
        ->placeholder('Select time...');

    // Color Picker
    $themeColorPicker = ColorPicker::make('theme_color')
        ->label('Theme Color')
        ->default('#6366f1');

    // File Upload
    $avatarUpload = FileUpload::make('avatar')
        ->label('Profile Picture')
        ->image()
        ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/gif'])
        ->helperText('JPG, PNG or GIF. Max 2MB.');

    $documentsUpload = FileUpload::make('documents')
        ->label('Documents')
        ->multiple()
        ->maxFiles(5)
        ->helperText('Upload up to 5 files');

    // Tags Input
    $skillsInput = TagsInput::make('skills')
        ->label('Skills')
        ->placeholder('Add a skill...')
        ->suggestions(['PHP', 'Laravel', 'JavaScript', 'Vue.js', 'React', 'Python'])
        ->default(['PHP', 'Laravel']);

    // Key Value
    $metaKeyValue = KeyValue::make('meta')
        ->label('Custom Meta Data')
        ->keyLabel('Key')
        ->valueLabel('Value')
        ->default([
            'version' => '1.0',
            'status' => 'active',
        ]);

    // Repeater
    $teamRepeater = Repeater::make('team')
        ->label('Team Members')
        ->schema([
            TextInput::make('name')->label('Name')->required(),
            TextInput::make('email')->label('Email')->required(),
        ])
        ->default([
            ['name' => 'John Doe', 'email' => 'john@example.com'],
        ])
        ->minItems(1)
        ->maxItems(5)
        ->collapsible()
        ->reorderable();

    // Rich Editor
    $contentEditor = RichEditor::make('content')
        ->label('Article Content')
        ->toolbarButtons(['bold', 'italic', 'underline', 'link', 'bulletList', 'orderedList'])
        ->default('<p>Write your content here...</p>');

    // Markdown Editor
    $readmeEditor = MarkdownEditor::make('readme')
        ->label('README')
        ->withPreview()
        ->default("# Project Title\n\nDescription here...");

    // PIN Input
    $verificationPin = PinInput::make('verification_code')
        ->label('Verification Code')
        ->length(6)
        ->otp();

    // Rate Input
    $ratingInput = RateInput::make('rating')
        ->label('Your Rating')
        ->maxRating(5)
        ->default(4)
        ->showValue();

    // Icon Picker
    $iconPicker = IconPicker::make('icon')
        ->label('Select Icon')
        ->searchable()
        ->gridColumns(6)
        ->default('star');
@endphp

<section class="rounded-xl p-6 mb-6 border border-[var(--docs-border)]" style="background: var(--docs-bg-alt);">
    <div class="flex items-center gap-3 mb-2">
        <span class="w-2.5 h-2.5 bg-indigo-500 rounded-full"></span>
        <h3 class="text-lg font-semibold" style="color: var(--docs-text);">Accelade Forms</h3>
    </div>
    <p class="text-sm mb-4" style="color: var(--docs-text-muted);">
        A comprehensive form builder library for Laravel. Build complex forms with a fluent PHP API.
    </p>

    <div class="space-y-4 mb-4">
        <!-- Quick Start -->
        <div class="rounded-xl p-4 border border-indigo-500/30" style="background: rgba(99, 102, 241, 0.1);">
            <h4 class="font-medium mb-3 flex items-center gap-2" style="color: var(--docs-text);">
                <span class="text-xs px-2 py-1 bg-indigo-500/20 text-indigo-500 rounded">Quick Start</span>
                Installation
            </h4>
            <p class="text-sm mb-4" style="color: var(--docs-text-muted);">
                Install the package via Composer:
            </p>
            <x-accelade::code-block language="bash">
composer require accelade/forms
            </x-accelade::code-block>
        </div>

        <!-- All Components Test Form -->
        <div class="rounded-xl p-4 border border-emerald-500/30" style="background: rgba(16, 185, 129, 0.1);">
            <h4 class="font-medium mb-3 flex items-center gap-2" style="color: var(--docs-text);">
                <span class="text-xs px-2 py-1 bg-emerald-500/20 text-emerald-500 rounded">Test Form</span>
                All Components Demo
            </h4>
            <p class="text-sm mb-4" style="color: var(--docs-text-muted);">
                This form contains all available form components for testing:
            </p>

            <form class="space-y-6">
                {{-- Section: Basic Inputs --}}
                <div class="border-b border-[var(--docs-border)] pb-6">
                    <h5 class="text-sm font-semibold mb-4" style="color: var(--docs-text);">Basic Inputs</h5>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        {!! $nameInput !!}
                        {!! $emailInput !!}
                        {!! $passwordInput !!}
                    </div>
                    <div class="mt-4">
                        {!! $bioTextarea !!}
                    </div>
                </div>

                {{-- Section: Selection Components --}}
                <div class="border-b border-[var(--docs-border)] pb-6">
                    <h5 class="text-sm font-semibold mb-4" style="color: var(--docs-text);">Selection Components</h5>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        {!! $countrySelect !!}
                        {!! $rolesSelect !!}
                    </div>
                </div>

                {{-- Section: Boolean Components --}}
                <div class="border-b border-[var(--docs-border)] pb-6">
                    <h5 class="text-sm font-semibold mb-4" style="color: var(--docs-text);">Boolean Components</h5>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-4">
                            {!! $termsCheckbox !!}
                            {!! $notificationsToggle !!}
                        </div>
                        <div>
                            {!! $genderRadio !!}
                        </div>
                    </div>
                    <div class="mt-4">
                        {!! $interestsCheckboxList !!}
                    </div>
                </div>

                {{-- Section: Numeric Components --}}
                <div class="border-b border-[var(--docs-border)] pb-6">
                    <h5 class="text-sm font-semibold mb-4" style="color: var(--docs-text);">Numeric Components</h5>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        {!! $ageField !!}
                        {!! $priceField !!}
                    </div>
                    <div class="mt-4">
                        {!! $volumeSlider !!}
                    </div>
                </div>

                {{-- Section: Date/Time Components --}}
                <div class="border-b border-[var(--docs-border)] pb-6">
                    <h5 class="text-sm font-semibold mb-4" style="color: var(--docs-text);">Date & Time</h5>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        {!! $birthdatePicker !!}
                        {!! $meetingTimePicker !!}
                    </div>
                </div>

                {{-- Section: Color & Icon --}}
                <div class="border-b border-[var(--docs-border)] pb-6">
                    <h5 class="text-sm font-semibold mb-4" style="color: var(--docs-text);">Color & Icon</h5>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        {!! $themeColorPicker !!}
                        {!! $iconPicker !!}
                    </div>
                </div>

                {{-- Section: File Uploads --}}
                <div class="border-b border-[var(--docs-border)] pb-6">
                    <h5 class="text-sm font-semibold mb-4" style="color: var(--docs-text);">File Uploads</h5>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        {!! $avatarUpload !!}
                        {!! $documentsUpload !!}
                    </div>
                </div>

                {{-- Section: Tags Input --}}
                <div class="border-b border-[var(--docs-border)] pb-6">
                    <h5 class="text-sm font-semibold mb-4" style="color: var(--docs-text);">Tags Input</h5>
                    {!! $skillsInput !!}
                </div>

                {{-- Section: Key Value --}}
                <div class="border-b border-[var(--docs-border)] pb-6">
                    <h5 class="text-sm font-semibold mb-4" style="color: var(--docs-text);">Key Value</h5>
                    {!! $metaKeyValue !!}
                </div>

                {{-- Section: Repeater --}}
                <div class="border-b border-[var(--docs-border)] pb-6">
                    <h5 class="text-sm font-semibold mb-4" style="color: var(--docs-text);">Repeater</h5>
                    {!! $teamRepeater !!}
                </div>

                {{-- Section: Rich Text Editors --}}
                <div class="border-b border-[var(--docs-border)] pb-6">
                    <h5 class="text-sm font-semibold mb-4" style="color: var(--docs-text);">Rich Text Editors</h5>
                    <div class="space-y-4">
                        {!! $contentEditor !!}
                        {!! $readmeEditor !!}
                    </div>
                </div>

                {{-- Section: Special Inputs --}}
                <div class="border-b border-[var(--docs-border)] pb-6">
                    <h5 class="text-sm font-semibold mb-4" style="color: var(--docs-text);">Special Inputs</h5>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        {!! $verificationPin !!}
                        {!! $ratingInput !!}
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="flex gap-4">
                    <button type="submit" class="inline-flex items-center px-6 py-3 bg-emerald-600 border border-transparent rounded-lg font-semibold text-sm text-white uppercase tracking-widest hover:bg-emerald-700 transition">
                        Submit Form
                    </button>
                    <button type="reset" class="inline-flex items-center px-6 py-3 border rounded-lg font-semibold text-sm uppercase tracking-widest transition" style="border-color: var(--docs-border); color: var(--docs-text);">
                        Reset
                    </button>
                </div>
            </form>
        </div>

        <!-- Available Components Grid -->
        <div class="rounded-xl p-4 border border-amber-500/30" style="background: rgba(245, 158, 11, 0.1);">
            <h4 class="font-medium mb-3 flex items-center gap-2" style="color: var(--docs-text);">
                <span class="text-xs px-2 py-1 bg-amber-500/20 text-amber-500 rounded">Components</span>
                Available Form Fields
            </h4>
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-2 text-sm" style="color: var(--docs-text-muted);">
                <div class="p-2 rounded border border-[var(--docs-border)]" style="background: var(--docs-bg);">TextInput</div>
                <div class="p-2 rounded border border-[var(--docs-border)]" style="background: var(--docs-bg);">Textarea</div>
                <div class="p-2 rounded border border-[var(--docs-border)]" style="background: var(--docs-bg);">Select</div>
                <div class="p-2 rounded border border-[var(--docs-border)]" style="background: var(--docs-bg);">Checkbox</div>
                <div class="p-2 rounded border border-[var(--docs-border)]" style="background: var(--docs-bg);">Radio</div>
                <div class="p-2 rounded border border-[var(--docs-border)]" style="background: var(--docs-bg);">Toggle</div>
                <div class="p-2 rounded border border-[var(--docs-border)]" style="background: var(--docs-bg);">NumberField</div>
                <div class="p-2 rounded border border-[var(--docs-border)]" style="background: var(--docs-bg);">Slider</div>
                <div class="p-2 rounded border border-[var(--docs-border)]" style="background: var(--docs-bg);">DatePicker</div>
                <div class="p-2 rounded border border-[var(--docs-border)]" style="background: var(--docs-bg);">TimePicker</div>
                <div class="p-2 rounded border border-[var(--docs-border)]" style="background: var(--docs-bg);">ColorPicker</div>
                <div class="p-2 rounded border border-[var(--docs-border)]" style="background: var(--docs-bg);">FileUpload</div>
                <div class="p-2 rounded border border-[var(--docs-border)]" style="background: var(--docs-bg);">TagsInput</div>
                <div class="p-2 rounded border border-[var(--docs-border)]" style="background: var(--docs-bg);">KeyValue</div>
                <div class="p-2 rounded border border-[var(--docs-border)]" style="background: var(--docs-bg);">Repeater</div>
                <div class="p-2 rounded border border-[var(--docs-border)]" style="background: var(--docs-bg);">RichEditor</div>
                <div class="p-2 rounded border border-[var(--docs-border)]" style="background: var(--docs-bg);">MarkdownEditor</div>
                <div class="p-2 rounded border border-[var(--docs-border)]" style="background: var(--docs-bg);">PinInput</div>
                <div class="p-2 rounded border border-[var(--docs-border)]" style="background: var(--docs-bg);">RateInput</div>
                <div class="p-2 rounded border border-[var(--docs-border)]" style="background: var(--docs-bg);">IconPicker</div>
            </div>
        </div>

        <!-- Form Container Demo -->
        <div class="rounded-xl p-4 border border-sky-500/30" style="background: rgba(14, 165, 233, 0.1);">
            <h4 class="font-medium mb-3 flex items-center gap-2" style="color: var(--docs-text);">
                <span class="text-xs px-2 py-1 bg-sky-500/20 text-sky-500 rounded">Form</span>
                Form Container Features
            </h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                <div class="flex items-center gap-3 p-3 rounded-lg" style="background: var(--docs-bg);">
                    <span class="text-sky-500 font-mono text-sm">stay()</span>
                    <span class="text-sm" style="color: var(--docs-text-muted);">Stay on page after success</span>
                </div>
                <div class="flex items-center gap-3 p-3 rounded-lg" style="background: var(--docs-bg);">
                    <span class="text-sky-500 font-mono text-sm">preserveScroll()</span>
                    <span class="text-sm" style="color: var(--docs-text-muted);">Keep scroll position</span>
                </div>
                <div class="flex items-center gap-3 p-3 rounded-lg" style="background: var(--docs-bg);">
                    <span class="text-sky-500 font-mono text-sm">resetOnSuccess()</span>
                    <span class="text-sm" style="color: var(--docs-text-muted);">Clear form on success</span>
                </div>
                <div class="flex items-center gap-3 p-3 rounded-lg" style="background: var(--docs-bg);">
                    <span class="text-sky-500 font-mono text-sm">confirm()</span>
                    <span class="text-sm" style="color: var(--docs-text-muted);">Confirmation dialog</span>
                </div>
                <div class="flex items-center gap-3 p-3 rounded-lg" style="background: var(--docs-bg);">
                    <span class="text-sky-500 font-mono text-sm">requirePassword()</span>
                    <span class="text-sm" style="color: var(--docs-text-muted);">Password confirmation</span>
                </div>
                <div class="flex items-center gap-3 p-3 rounded-lg" style="background: var(--docs-bg);">
                    <span class="text-sky-500 font-mono text-sm">submitOnChange()</span>
                    <span class="text-sm" style="color: var(--docs-text-muted);">Auto-submit on change</span>
                </div>
            </div>
        </div>

        <!-- Model Binding Demo -->
        <div class="rounded-xl p-4 border border-purple-500/30" style="background: rgba(168, 85, 247, 0.1);">
            <h4 class="font-medium mb-3 flex items-center gap-2" style="color: var(--docs-text);">
                <span class="text-xs px-2 py-1 bg-purple-500/20 text-purple-500 rounded">Model</span>
                Model Binding
            </h4>
            <p class="text-sm mb-4" style="color: var(--docs-text-muted);">
                Pre-fill forms with Eloquent model data:
            </p>
            <form class="space-y-3">
                <div class="form-field">
                    <label class="block text-sm font-medium mb-1" style="color: var(--docs-text);">Name</label>
                    <input type="text" class="px-3 py-2 text-sm rounded-md border focus:ring-2 focus:ring-purple-500 focus:border-purple-500 outline-none w-full" style="border-color: var(--docs-border); background: var(--docs-bg); color: var(--docs-text);" value="John Doe">
                </div>
                <div class="form-field">
                    <label class="block text-sm font-medium mb-1" style="color: var(--docs-text);">Email</label>
                    <input type="email" class="px-3 py-2 text-sm rounded-md border focus:ring-2 focus:ring-purple-500 focus:border-purple-500 outline-none w-full" style="border-color: var(--docs-border); background: var(--docs-bg); color: var(--docs-text);" value="john@example.com">
                </div>
                <p class="text-xs italic" style="color: var(--docs-text-muted);">
                    Fields are pre-filled from the bound model using <code class="text-purple-500">->model($user)</code>
                </p>
            </form>
        </div>
    </div>

    <!-- Features List -->
    <div class="rounded-xl p-4 border border-[var(--docs-border)]" style="background: var(--docs-bg);">
        <h4 class="font-medium mb-4" style="color: var(--docs-text);">Key Features</h4>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
            <div class="flex items-start gap-3">
                <span class="text-emerald-500">&#10003;</span>
                <div style="color: var(--docs-text-muted);">
                    <strong style="color: var(--docs-text);">Fluent PHP API</strong><br>
                    Build forms with chainable methods
                </div>
            </div>
            <div class="flex items-start gap-3">
                <span class="text-emerald-500">&#10003;</span>
                <div style="color: var(--docs-text-muted);">
                    <strong style="color: var(--docs-text);">Dark/Light Mode</strong><br>
                    Full theme support out of the box
                </div>
            </div>
            <div class="flex items-start gap-3">
                <span class="text-emerald-500">&#10003;</span>
                <div style="color: var(--docs-text-muted);">
                    <strong style="color: var(--docs-text);">RTL Support</strong><br>
                    Right-to-left language support
                </div>
            </div>
            <div class="flex items-start gap-3">
                <span class="text-emerald-500">&#10003;</span>
                <div style="color: var(--docs-text-muted);">
                    <strong style="color: var(--docs-text);">Validation</strong><br>
                    Built-in validation rules
                </div>
            </div>
            <div class="flex items-start gap-3">
                <span class="text-emerald-500">&#10003;</span>
                <div style="color: var(--docs-text-muted);">
                    <strong style="color: var(--docs-text);">Translations</strong><br>
                    Multi-language support
                </div>
            </div>
            <div class="flex items-start gap-3">
                <span class="text-emerald-500">&#10003;</span>
                <div style="color: var(--docs-text-muted);">
                    <strong style="color: var(--docs-text);">Accelade Integration</strong><br>
                    Works with all Accelade frameworks
                </div>
            </div>
        </div>
    </div>

    <x-accelade::code-block language="php" filename="forms-example.php" class="mt-4">
use Accelade\Forms\Form;
use Accelade\Forms\Components\TextInput;
use Accelade\Forms\Components\Select;
use Accelade\Forms\Components\Textarea;

// Create a complete form
Form::make('contact')
    ->action('/contact')
    ->method('POST')
    ->schema([
        TextInput::make('name')
            ->label('Name')
            ->required()
            ->placeholder('Enter your name'),

        TextInput::make('email')
            ->label('Email')
            ->email()
            ->required(),

        Select::make('country')
            ->label('Country')
            ->options([
                'us' => 'United States',
                'uk' => 'United Kingdom',
                'ca' => 'Canada',
            ])
            ->searchable(),

        Textarea::make('message')
            ->label('Message')
            ->rows(4)
            ->placeholder('Your message...'),
    ])
    ->submitLabel('Send Message')
    ->resetOnSuccess();
    </x-accelade::code-block>
</section>
