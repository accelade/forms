{{-- File Upload Component Section --}}
@props(['prefix' => 'a'])

@php
    use Accelade\Forms\Components\FileUpload;

    // Basic file upload (native mode)
    $nativeUpload = FileUpload::make('native_file')
        ->label('Native File Input')
        ->native()
        ->helperText('Simple HTML file input with drag & drop');

    // FilePond file upload (default)
    $filepondUpload = FileUpload::make('filepond_file')
        ->label('FilePond Upload')
        ->helperText('FilePond-powered with all plugins');

    // Image upload with preview
    $imageUpload = FileUpload::make('image_file')
        ->label('Image Upload')
        ->image()
        ->imagePreview()
        ->maxSize(5120)
        ->helperText('JPG, PNG, GIF, WebP - Max 5MB');

    // Avatar upload (circular)
    $avatarUpload = FileUpload::make('avatar_file')
        ->label('Avatar')
        ->avatar()
        ->imageEditor()
        ->circleCropper()
        ->helperText('1:1 aspect ratio with circle cropper');

    // Multiple files
    $multipleUpload = FileUpload::make('multiple_files')
        ->label('Multiple Files')
        ->multiple()
        ->maxFiles(5)
        ->reorderable()
        ->downloadable()
        ->helperText('Upload up to 5 files, drag to reorder');

    // Image editor
    $editorUpload = FileUpload::make('edited_image')
        ->label('Image with Editor')
        ->image()
        ->imageEditor()
        ->imageEditorAspectRatios(['16:9', '4:3', '1:1', 'free'])
        ->helperText('Crop, rotate, flip with Cropper.js');

    // With aspect ratio crop
    $cropUpload = FileUpload::make('cropped_image')
        ->label('Fixed Aspect Ratio')
        ->image()
        ->imageCropAspectRatio('16:9')
        ->imageResizeTargetWidth(1920)
        ->imageResizeTargetHeight(1080)
        ->helperText('Auto-crop to 16:9 and resize to 1920x1080');
@endphp

<section class="rounded-xl p-6 mb-6 border border-[var(--docs-border)]" style="background: var(--docs-bg-alt);">
    <div class="flex items-center gap-3 mb-2">
        <span class="w-2.5 h-2.5 bg-indigo-500 rounded-full"></span>
        <h3 class="text-lg font-semibold" style="color: var(--docs-text);">File Upload</h3>
    </div>
    <p class="text-sm mb-4" style="color: var(--docs-text-muted);">
        Comprehensive file upload with FilePond integration, image editing, and Spatie Media Library support.
    </p>

    <div class="space-y-4 mb-4">
        <!-- Native Mode -->
        <div class="rounded-xl p-4 border border-gray-500/30" style="background: rgba(107, 114, 128, 0.1);">
            <h4 class="font-medium mb-3 flex items-center gap-2" style="color: var(--docs-text);">
                <span class="text-xs px-2 py-1 bg-gray-500/20 text-gray-500 rounded">Native</span>
                Simple HTML Upload
            </h4>

            {!! $nativeUpload !!}
        </div>

        <!-- FilePond Mode -->
        <div class="rounded-xl p-4 border border-indigo-500/30" style="background: rgba(99, 102, 241, 0.1);">
            <h4 class="font-medium mb-3 flex items-center gap-2" style="color: var(--docs-text);">
                <span class="text-xs px-2 py-1 bg-indigo-500/20 text-indigo-500 rounded">FilePond</span>
                FilePond Integration
            </h4>

            {!! $filepondUpload !!}
        </div>

        <!-- Image Upload -->
        <div class="rounded-xl p-4 border border-emerald-500/30" style="background: rgba(16, 185, 129, 0.1);">
            <h4 class="font-medium mb-3 flex items-center gap-2" style="color: var(--docs-text);">
                <span class="text-xs px-2 py-1 bg-emerald-500/20 text-emerald-500 rounded">Image</span>
                Image Upload with Preview
            </h4>

            {!! $imageUpload !!}
        </div>

        <!-- Avatar Mode -->
        <div class="rounded-xl p-4 border border-amber-500/30" style="background: rgba(245, 158, 11, 0.1);">
            <h4 class="font-medium mb-3 flex items-center gap-2" style="color: var(--docs-text);">
                <span class="text-xs px-2 py-1 bg-amber-500/20 text-amber-500 rounded">Avatar</span>
                Circular Avatar Upload
            </h4>

            {!! $avatarUpload !!}
        </div>

        <!-- Multiple Files -->
        <div class="rounded-xl p-4 border border-sky-500/30" style="background: rgba(14, 165, 233, 0.1);">
            <h4 class="font-medium mb-3 flex items-center gap-2" style="color: var(--docs-text);">
                <span class="text-xs px-2 py-1 bg-sky-500/20 text-sky-500 rounded">Multiple</span>
                Multiple Files with Reorder
            </h4>

            {!! $multipleUpload !!}
        </div>

        <!-- Image Editor -->
        <div class="rounded-xl p-4 border border-purple-500/30" style="background: rgba(168, 85, 247, 0.1);">
            <h4 class="font-medium mb-3 flex items-center gap-2" style="color: var(--docs-text);">
                <span class="text-xs px-2 py-1 bg-purple-500/20 text-purple-500 rounded">Editor</span>
                Image Editor with Cropper.js
            </h4>

            {!! $editorUpload !!}
        </div>

        <!-- Fixed Aspect Ratio -->
        <div class="rounded-xl p-4 border border-rose-500/30" style="background: rgba(244, 63, 94, 0.1);">
            <h4 class="font-medium mb-3 flex items-center gap-2" style="color: var(--docs-text);">
                <span class="text-xs px-2 py-1 bg-rose-500/20 text-rose-500 rounded">Crop</span>
                Auto-Crop & Resize
            </h4>

            {!! $cropUpload !!}
        </div>

        <!-- API Reference -->
        <div class="rounded-xl p-4 border border-cyan-500/30" style="background: rgba(6, 182, 212, 0.1);">
            <h4 class="font-medium mb-3 flex items-center gap-2" style="color: var(--docs-text);">
                <span class="text-xs px-2 py-1 bg-cyan-500/20 text-cyan-500 rounded">API</span>
                Filament-Compatible API
            </h4>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                <div class="p-3 rounded-lg" style="background: var(--docs-bg);">
                    <span class="text-cyan-500 font-mono text-sm">->disk('s3')</span>
                    <p class="text-xs mt-1" style="color: var(--docs-text-muted);">Storage disk</p>
                </div>
                <div class="p-3 rounded-lg" style="background: var(--docs-bg);">
                    <span class="text-cyan-500 font-mono text-sm">->directory('uploads')</span>
                    <p class="text-xs mt-1" style="color: var(--docs-text-muted);">Storage directory</p>
                </div>
                <div class="p-3 rounded-lg" style="background: var(--docs-bg);">
                    <span class="text-cyan-500 font-mono text-sm">->visibility('private')</span>
                    <p class="text-xs mt-1" style="color: var(--docs-text-muted);">File visibility</p>
                </div>
                <div class="p-3 rounded-lg" style="background: var(--docs-bg);">
                    <span class="text-cyan-500 font-mono text-sm">->collection('photos')</span>
                    <p class="text-xs mt-1" style="color: var(--docs-text-muted);">Spatie Media Library</p>
                </div>
                <div class="p-3 rounded-lg" style="background: var(--docs-bg);">
                    <span class="text-cyan-500 font-mono text-sm">->mediaBrowser()</span>
                    <p class="text-xs mt-1" style="color: var(--docs-text-muted);">WordPress-style picker</p>
                </div>
                <div class="p-3 rounded-lg" style="background: var(--docs-bg);">
                    <span class="text-cyan-500 font-mono text-sm">->native()</span>
                    <p class="text-xs mt-1" style="color: var(--docs-text-muted);">Simple HTML fallback</p>
                </div>
            </div>
        </div>
    </div>

    <x-accelade::code-block language="php" filename="file-upload-examples.php">
use Accelade\Forms\Components\FileUpload;

// Basic FilePond upload
FileUpload::make('document')
    ->label('Document')
    ->maxSize(10240);

// Native HTML file input
FileUpload::make('simple_file')
    ->label('Simple Upload')
    ->native();

// Avatar with circle cropper
FileUpload::make('avatar')
    ->label('Profile Photo')
    ->avatar()
    ->imageEditor()
    ->circleCropper();

// Image with editor and aspect ratios
FileUpload::make('banner')
    ->label('Banner Image')
    ->image()
    ->imageEditor()
    ->imageEditorAspectRatios(['16:9', '4:3', '1:1'])
    ->imageResizeTargetWidth(1920)
    ->imageResizeTargetHeight(1080);

// Multiple files with reorder
FileUpload::make('gallery')
    ->label('Gallery')
    ->multiple()
    ->maxFiles(10)
    ->reorderable()
    ->downloadable()
    ->openable();

// Spatie Media Library integration
FileUpload::make('photos')
    ->label('Photos')
    ->collection('gallery')
    ->multiple()
    ->mediaBrowser();

// Private file storage
FileUpload::make('contracts')
    ->label('Contracts')
    ->disk('s3')
    ->directory('contracts')
    ->visibility('private')
    ->acceptedFileTypes(['application/pdf']);
    </x-accelade::code-block>
</section>
