{{-- Form Component Section --}}
@props(['prefix' => 'a'])

<section class="rounded-xl p-6 mb-6 border border-[var(--docs-border)]" style="background: var(--docs-bg-alt);">
    <div class="flex items-center gap-3 mb-2">
        <span class="w-2.5 h-2.5 bg-indigo-500 rounded-full"></span>
        <h3 class="text-lg font-semibold" style="color: var(--docs-text);">Form</h3>
    </div>
    <p class="text-sm mb-4" style="color: var(--docs-text-muted);">
        Form container with submission handling, confirmation dialogs, and model binding.
    </p>

    <div class="space-y-4 mb-4">
        <!-- Basic Form -->
        <div class="rounded-xl p-4 border border-indigo-500/30" style="background: rgba(99, 102, 241, 0.1);">
            <h4 class="font-medium mb-3 flex items-center gap-2" style="color: var(--docs-text);">
                <span class="text-xs px-2 py-1 bg-indigo-500/20 text-indigo-500 rounded">Basic</span>
                Contact Form
            </h4>

            <form class="space-y-4">
                <div class="form-field">
                    <label class="form-label">Name <span class="required-indicator">*</span></label>
                    <input type="text" class="form-input" placeholder="Your name" required>
                </div>
                <div class="form-field">
                    <label class="form-label">Email <span class="required-indicator">*</span></label>
                    <input type="email" class="form-input" placeholder="your@email.com" required>
                </div>
                <div class="form-field">
                    <label class="form-label">Message</label>
                    <textarea class="form-input" rows="3" placeholder="Your message..."></textarea>
                </div>
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                    Send Message
                </button>
            </form>
        </div>

        <!-- Submission Behavior -->
        <div class="rounded-xl p-4 border border-emerald-500/30" style="background: rgba(16, 185, 129, 0.1);">
            <h4 class="font-medium mb-3 flex items-center gap-2" style="color: var(--docs-text);">
                <span class="text-xs px-2 py-1 bg-emerald-500/20 text-emerald-500 rounded">Behavior</span>
                Submission Options
            </h4>

            <div class="space-y-3">
                <div class="flex items-center gap-3 p-3 rounded-lg bg-white/50 dark:bg-gray-800/50">
                    <span class="text-emerald-500 font-mono text-sm">stay()</span>
                    <span style="color: var(--docs-text);">Stay on page after success</span>
                </div>
                <div class="flex items-center gap-3 p-3 rounded-lg bg-white/50 dark:bg-gray-800/50">
                    <span class="text-emerald-500 font-mono text-sm">preserveScroll()</span>
                    <span style="color: var(--docs-text);">Keep scroll position</span>
                </div>
                <div class="flex items-center gap-3 p-3 rounded-lg bg-white/50 dark:bg-gray-800/50">
                    <span class="text-emerald-500 font-mono text-sm">resetOnSuccess()</span>
                    <span style="color: var(--docs-text);">Clear form after success</span>
                </div>
                <div class="flex items-center gap-3 p-3 rounded-lg bg-white/50 dark:bg-gray-800/50">
                    <span class="text-emerald-500 font-mono text-sm">background()</span>
                    <span style="color: var(--docs-text);">Keep inputs enabled during submit</span>
                </div>
            </div>
        </div>

        <!-- Confirmation Dialog -->
        <div class="rounded-xl p-4 border border-amber-500/30" style="background: rgba(245, 158, 11, 0.1);">
            <h4 class="font-medium mb-3 flex items-center gap-2" style="color: var(--docs-text);">
                <span class="text-xs px-2 py-1 bg-amber-500/20 text-amber-500 rounded">Confirm</span>
                Confirmation Dialog
            </h4>

            <div class="p-4 rounded-lg bg-white/80 dark:bg-gray-800/80 border border-amber-200 dark:border-amber-800">
                <div class="text-center">
                    <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-amber-100 dark:bg-amber-900 mb-3">
                        <svg class="h-6 w-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium mb-2" style="color: var(--docs-text);">Are you sure?</h3>
                    <p class="text-sm mb-4" style="color: var(--docs-text-muted);">This action cannot be undone.</p>
                    <div class="flex gap-3 justify-center">
                        <button class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 rounded-md text-sm">Cancel</button>
                        <button class="px-4 py-2 bg-red-600 text-white rounded-md text-sm">Delete</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Model Binding -->
        <div class="rounded-xl p-4 border border-sky-500/30" style="background: rgba(14, 165, 233, 0.1);">
            <h4 class="font-medium mb-3 flex items-center gap-2" style="color: var(--docs-text);">
                <span class="text-xs px-2 py-1 bg-sky-500/20 text-sky-500 rounded">Model</span>
                Model Binding
            </h4>

            <form class="space-y-4">
                <div class="form-field">
                    <label class="form-label">Name</label>
                    <input type="text" class="form-input" value="John Doe" placeholder="Name from model">
                </div>
                <div class="form-field">
                    <label class="form-label">Email</label>
                    <input type="email" class="form-input" value="john@example.com" placeholder="Email from model">
                </div>
                <p class="text-xs italic" style="color: var(--docs-text-muted);">
                    Fields are pre-filled from the bound model.
                </p>
            </form>
        </div>

        <!-- Selective Unguarding -->
        <div class="rounded-xl p-4 border border-purple-500/30" style="background: rgba(168, 85, 247, 0.1);">
            <h4 class="font-medium mb-3 flex items-center gap-2" style="color: var(--docs-text);">
                <span class="text-xs px-2 py-1 bg-purple-500/20 text-purple-500 rounded">Unguard</span>
                Selective Unguarding
            </h4>

            <div class="space-y-3">
                <div class="flex items-center gap-3 p-3 rounded-lg bg-white/50 dark:bg-gray-800/50">
                    <span class="text-purple-500 font-mono text-sm">unguarded()</span>
                    <span style="color: var(--docs-text);">All fields unguarded</span>
                </div>
                <div class="flex items-center gap-3 p-3 rounded-lg bg-white/50 dark:bg-gray-800/50">
                    <span class="text-purple-500 font-mono text-sm">unguarded(['name', 'email'])</span>
                    <span style="color: var(--docs-text);">Only specific fields unguarded</span>
                </div>
                <div class="flex items-center gap-3 p-3 rounded-lg bg-white/50 dark:bg-gray-800/50">
                    <span class="text-purple-500 font-mono text-sm">Form::defaultUnguarded()</span>
                    <span style="color: var(--docs-text);">Global default setting</span>
                </div>
                <div class="flex items-center gap-3 p-3 rounded-lg bg-white/50 dark:bg-gray-800/50">
                    <span class="text-purple-500 font-mono text-sm">Form::guardWhen($callback)</span>
                    <span style="color: var(--docs-text);">Conditional guarding by model</span>
                </div>
            </div>
        </div>
    </div>

    <x-accelade::code-block language="php" filename="form-examples.php">
use Accelade\Forms\Form;
use Accelade\Forms\Components\TextInput;
use Accelade\Forms\Components\Textarea;
use Accelade\Forms\Components\Submit;

// Basic form
Form::make('contact')
    ->action('/contact')
    ->method('POST')
    ->schema([
        TextInput::make('name')->required(),
        TextInput::make('email')->email()->required(),
        Textarea::make('message'),
    ])
    ->submitLabel('Send Message');

// Submission behavior
Form::make('settings')
    ->stay()              // Stay on page
    ->preserveScroll()    // Keep scroll position
    ->resetOnSuccess()    // Clear form on success
    ->background();       // Keep inputs enabled

// Confirmation dialog
Form::make('delete-account')
    ->action('/account/delete')
    ->method('DELETE')
    ->confirm('Are you sure you want to delete your account?')
    ->confirmButton('Yes, delete')
    ->cancelButton('Cancel')
    ->confirmDanger();

// Model binding
Form::make('profile')
    ->model($user)
    ->schema([
        TextInput::make('name'),
        TextInput::make('email')->email(),
    ]);

// Selective unguarding
Form::make('profile')
    ->model($user)
    ->unguarded(['name', 'email']);  // Only these fields

// Global configuration (in AppServiceProvider)
Form::defaultUnguarded(['name', 'email', 'bio']);

// Conditional guarding by model type
Form::guardWhen(function ($model) {
    return $model instanceof Admin;  // Guard admin models
});
    </x-accelade::code-block>
</section>
