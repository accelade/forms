# File Upload

The FileUpload component provides comprehensive file upload functionality with FilePond integration, image editing capabilities, and Spatie Media Library support. The API is fully compatible with [Filament's FileUpload](https://filamentphp.com/docs/4.x/forms/file-upload).

## Basic Usage

```php
use Accelade\Forms\Components\FileUpload;

FileUpload::make('avatar')
    ->label('Profile Picture');
```

By default, files are uploaded using FilePond with all plugins enabled.

## Native Mode

For a simple HTML file input without FilePond:

```php
FileUpload::make('document')
    ->label('Document')
    ->native();
```

## Storage Configuration

Configure where files are stored:

```php
FileUpload::make('document')
    ->disk('s3')           // Storage disk
    ->directory('uploads') // Directory path
    ->visibility('private'); // public or private
```

## Accept File Types

Restrict allowed file types:

```php
FileUpload::make('document')
    ->label('Document')
    ->acceptedFileTypes(['application/pdf', '.doc', '.docx']);
```

Or use the alias:

```php
FileUpload::make('document')
    ->accept('application/pdf');
```

## Image Upload

Configure for image uploads with preview:

```php
FileUpload::make('photo')
    ->label('Photo')
    ->image()
    ->imagePreview();
```

## Avatar Mode

Circular avatar upload with 1:1 aspect ratio:

```php
FileUpload::make('avatar')
    ->label('Profile Photo')
    ->avatar()
    ->imageEditor()
    ->circleCropper();
```

## Multiple Files

Allow multiple file uploads:

```php
FileUpload::make('attachments')
    ->label('Attachments')
    ->multiple()
    ->maxFiles(5)
    ->minFiles(1);
```

## File Size Limits

Set size restrictions (in kilobytes):

```php
FileUpload::make('video')
    ->label('Video')
    ->minSize(100)    // 100KB minimum
    ->maxSize(51200); // 50MB maximum
```

## Image Cropping

Enable automatic image cropping:

```php
FileUpload::make('banner')
    ->image()
    ->imageCropAspectRatio('16:9');
```

## Image Resize

Automatically resize images on upload:

```php
FileUpload::make('photo')
    ->image()
    ->imageResizeTargetWidth(1920)
    ->imageResizeTargetHeight(1080)
    ->imageResizeMode('contain'); // force, cover, contain
```

Or use the convenience method:

```php
FileUpload::make('photo')
    ->image()
    ->imageResize(1920, 1080, 'contain');
```

## Image Editor (Cropper.js)

Enable the built-in image editor with Cropper.js:

```php
FileUpload::make('photo')
    ->image()
    ->imageEditor()
    ->imageEditorAspectRatios(['16:9', '4:3', '1:1', 'free'])
    ->imageEditorMode(1); // Cropper.js viewMode: 0-3
```

Additional editor options:

```php
FileUpload::make('photo')
    ->imageEditor()
    ->imageEditorEmptyFillColor('#ffffff')
    ->imageEditorViewportWidth('400px')
    ->imageEditorViewportHeight('300px')
    ->circleCropper(); // Circular crop mask
```

## File Actions

Configure available file actions:

```php
FileUpload::make('document')
    ->downloadable()    // Show download button
    ->openable()        // Show open in new tab button
    ->deletable(false)  // Disable deletion
    ->previewable()     // Enable preview
    ->reorderable();    // Enable drag-to-reorder (for multiple)
```

## FilePond UI Options

Customize the FilePond panel layout:

```php
FileUpload::make('avatar')
    ->panelLayout('compact')      // compact, circle, integrated
    ->panelAspectRatio('1:1')
    ->imagePreviewHeight('200px')
    ->loadingIndicatorPosition('right')
    ->removeUploadedFileButtonPosition('right')
    ->uploadProgressIndicatorPosition('right');
```

## File Handling Options

```php
FileUpload::make('document')
    ->preserveFilenames()     // Keep original filenames
    ->appendFiles()           // Append to existing files
    ->prependFiles()          // Prepend to existing files
    ->orientImagesFromExif()  // Auto-rotate from EXIF
    ->fetchFileInformation()  // Fetch file metadata
    ->pasteable()             // Allow paste from clipboard
    ->maxParallelUploads(3);  // Limit concurrent uploads
```

Custom filename generation:

```php
FileUpload::make('photo')
    ->getUploadedFileNameForStorageUsing(function ($file) {
        return 'custom-' . time() . '.' . $file->getClientOriginalExtension();
    });
```

## Image Dimension Validation

Set dimension constraints for image uploads:

```php
FileUpload::make('banner')
    ->image()
    ->minWidth(800)
    ->maxWidth(1920)
    ->minHeight(400)
    ->maxHeight(1080);
```

Set exact dimensions:

```php
FileUpload::make('icon')
    ->image()
    ->width(512)
    ->height(512);
```

Or use the convenience method:

```php
FileUpload::make('thumbnail')
    ->image()
    ->imageDimensions(
        minWidth: 100,
        maxWidth: 1920,
        minHeight: 100,
        maxHeight: 1080
    );
```

### Resolution Constraints

Limit by total pixel count:

```php
FileUpload::make('photo')
    ->image()
    ->minResolution(100000)   // Minimum 100k pixels
    ->maxResolution(2000000); // Maximum 2M pixels
```

## Spatie Media Library Integration

Enable Spatie Media Library for advanced media management:

```php
FileUpload::make('photos')
    ->collection('gallery')      // Media collection name
    ->customProperties([         // Custom properties
        'alt' => 'Gallery image',
    ])
    ->customHeaders([            // Custom headers for upload
        'X-Custom-Header' => 'value',
    ])
    ->responsiveImages([         // Responsive image config
        'thumb' => ['width' => 200],
        'medium' => ['width' => 600],
    ])
    ->conversion('thumb');       // Apply media conversion
```

## Media Browser

Enable the WordPress-style media browser to select from existing files:

```php
FileUpload::make('featured_image')
    ->collection('images')
    ->mediaBrowser()
    ->multiple();
```

The media browser allows:
- Browse existing media organized by Model -> Collection
- Search files by name
- Grid/List view toggle
- Upload new files directly
- Preview panel with file details

## Complete Examples

### Avatar Upload

```php
FileUpload::make('avatar')
    ->label('Profile Photo')
    ->avatar()
    ->imageEditor()
    ->circleCropper()
    ->maxSize(2048)
    ->disk('public')
    ->directory('avatars');
```

### Document Upload

```php
FileUpload::make('documents')
    ->label('Documents')
    ->multiple()
    ->maxFiles(5)
    ->acceptedFileTypes(['application/pdf', 'image/*'])
    ->downloadable()
    ->reorderable()
    ->panelLayout('grid');
```

### Gallery with Media Library

```php
FileUpload::make('gallery')
    ->label('Gallery Images')
    ->collection('gallery')
    ->multiple()
    ->reorderable()
    ->mediaBrowser()
    ->image()
    ->imageEditor()
    ->imageEditorAspectRatios(['16:9', '4:3', '1:1']);
```

### Simple Native Upload

```php
FileUpload::make('attachment')
    ->label('Attachment')
    ->native()
    ->maxSize(5120);
```

## Methods Reference

### Storage Methods
| Method | Description |
|--------|-------------|
| `disk($disk)` | Set storage disk |
| `directory($path)` | Set upload directory |
| `visibility($visibility)` | Set file visibility (public/private) |

### File Type Methods
| Method | Description |
|--------|-------------|
| `acceptedFileTypes($types)` | Set allowed MIME types |
| `accept($types)` | Alias for acceptedFileTypes() |
| `image()` | Configure for images only |

### File Count Methods
| Method | Description |
|--------|-------------|
| `multiple()` | Allow multiple files |
| `maxFiles($count)` | Limit file count |
| `minFiles($count)` | Set minimum file count |

### File Size Methods
| Method | Description |
|--------|-------------|
| `maxSize($kb)` | Set maximum size in KB |
| `minSize($kb)` | Set minimum size in KB |

### Image Methods
| Method | Description |
|--------|-------------|
| `imagePreview()` | Enable image preview |
| `imageCrop($ratio)` | Enable cropping |
| `imageCropAspectRatio($ratio)` | Set crop aspect ratio |
| `imageResize($w, $h, $mode)` | Set resize dimensions |
| `imageResizeTargetWidth($px)` | Set target width |
| `imageResizeTargetHeight($px)` | Set target height |
| `imageResizeMode($mode)` | Set resize mode |
| `imageEditor()` | Enable Cropper.js editor |
| `imageEditorAspectRatios($ratios)` | Set editor aspect ratios |
| `imageEditorMode($mode)` | Set Cropper.js viewMode |
| `circleCropper()` | Enable circular crop |
| `avatar()` | Configure as avatar upload |

### Action Methods
| Method | Description |
|--------|-------------|
| `downloadable()` | Enable download button |
| `openable()` | Enable open in new tab |
| `deletable($bool)` | Enable/disable deletion |
| `previewable()` | Enable preview |
| `reorderable()` | Enable drag reorder |

### UI Methods
| Method | Description |
|--------|-------------|
| `panelLayout($layout)` | Set panel layout |
| `panelAspectRatio($ratio)` | Set panel aspect ratio |
| `imagePreviewHeight($height)` | Set preview height |
| `alignCenter()` | Center align the uploader |

### File Handling Methods
| Method | Description |
|--------|-------------|
| `preserveFilenames()` | Keep original filenames |
| `appendFiles()` | Append to existing files |
| `prependFiles()` | Prepend to existing files |
| `orientImagesFromExif()` | Auto-rotate from EXIF |
| `pasteable()` | Enable paste from clipboard |
| `maxParallelUploads($count)` | Limit concurrent uploads |

### Mode Methods
| Method | Description |
|--------|-------------|
| `native()` | Use simple HTML file input |

### Media Library Methods
| Method | Description |
|--------|-------------|
| `collection($name)` | Set media collection |
| `customProperties($props)` | Set custom properties |
| `customHeaders($headers)` | Set upload headers |
| `responsiveImages($config)` | Configure responsive images |
| `conversion($name)` | Apply media conversion |
| `mediaBrowser()` | Enable media browser |

### Dimension Validation Methods
| Method | Description |
|--------|-------------|
| `minWidth($px)` | Set minimum image width |
| `maxWidth($px)` | Set maximum image width |
| `minHeight($px)` | Set minimum image height |
| `maxHeight($px)` | Set maximum image height |
| `width($px)` | Set exact image width |
| `height($px)` | Set exact image height |
| `dimensions($w, $h)` | Set exact dimensions |
| `imageDimensions(...)` | Set all dimension constraints |
| `minResolution($px)` | Set minimum pixel count |
| `maxResolution($px)` | Set maximum pixel count |

## Configuration

Configure defaults in `config/forms.php`:

```php
'file_upload' => [
    'max_size' => 10240,              // 10MB default
    'disk' => 'public',
    'directory' => 'uploads',
    'visibility' => 'public',
    'token_lifetime' => 86400,        // 24 hours
    'temporary_url_expiration' => 3600, // 1 hour
],

'media_browser' => [
    'enabled' => true,
    'per_page' => 24,
],
```

## Security

All file upload operations are protected by encrypted tokens that contain:
- Storage disk and directory
- File size limits
- Accepted file types
- Media library collection
- Expiration timestamp

Tokens expire after 24 hours by default and cannot be tampered with.
