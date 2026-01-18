<?php

declare(strict_types=1);

namespace Accelade\Forms\Support;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;
use JsonSerializable;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * Represents an existing file that should be preserved during form updates.
 *
 * This class handles existing files in forms, allowing users to keep,
 * reorder, or remove existing files while uploading new ones.
 */
class ExistingFile implements Arrayable, Jsonable, JsonSerializable
{
    protected string $disk;

    protected string $path;

    protected ?string $name = null;

    protected ?string $previewUrl = null;

    protected ?int $size = null;

    protected ?string $mimeType = null;

    protected array $metadata = [];

    protected ?Media $media = null;

    /**
     * Create a new ExistingFile instance.
     */
    public function __construct(string $disk, string $path)
    {
        $this->disk = $disk;
        $this->path = $path;
    }

    /**
     * Create an ExistingFile from a specific disk.
     */
    public static function fromDisk(string $disk): static
    {
        return new static($disk, '');
    }

    /**
     * Get a file or array of files from the disk.
     *
     * @return static|Collection<int, static>
     */
    public function get(string|array $path): static|Collection
    {
        if (is_array($path)) {
            return collect($path)->map(fn ($p) => $this->get($p));
        }

        $instance = new static($this->disk, $path);
        $this->populateFileMetadata($instance, $path);

        return $instance;
    }

    /**
     * Populate file metadata for an existing file instance.
     */
    protected function populateFileMetadata(self $instance, string $path): void
    {
        if (! Storage::disk($this->disk)->exists($path)) {
            return;
        }

        $instance->name = basename($path);
        $instance->size = Storage::disk($this->disk)->size($path);
        $instance->mimeType = Storage::disk($this->disk)->mimeType($path);
        $instance->previewUrl = $this->generatePreviewUrl($path);
    }

    /**
     * Generate a preview URL for a file.
     */
    protected function generatePreviewUrl(string $path): string
    {
        try {
            return Storage::disk($this->disk)->temporaryUrl($path, now()->addHour());
        } catch (\RuntimeException) {
            return Storage::disk($this->disk)->url($path);
        }
    }

    /**
     * Create ExistingFile instances from Spatie Media Library media items.
     *
     * @param  \Illuminate\Support\Collection<int, Media>|Media  $media
     * @return Collection<int, static>|static
     */
    public static function fromMediaLibrary(Collection|Media $media): Collection|static
    {
        if ($media instanceof Media) {
            return static::fromSingleMedia($media);
        }

        return $media->map(fn ($item) => static::fromSingleMedia($item));
    }

    /**
     * Create an ExistingFile from a single Media item.
     */
    protected static function fromSingleMedia(Media $media): static
    {
        $instance = new static('', '');
        $instance->media = $media;
        $instance->name = $media->name;
        $instance->path = $media->getPath();
        $instance->size = $media->size;
        $instance->mimeType = $media->mime_type;

        // Get preview URL with conversion if available
        try {
            $instance->previewUrl = $media->getTemporaryUrl(
                now()->addHour(),
                $media->hasGeneratedConversion('thumb') ? 'thumb' : ''
            );
        } catch (\RuntimeException) {
            $instance->previewUrl = $media->getUrl(
                $media->hasGeneratedConversion('thumb') ? 'thumb' : ''
            );
        }

        return $instance;
    }

    /**
     * Attach metadata to the file.
     */
    public function metadata(array|string $key, mixed $value = null): static
    {
        if (is_array($key)) {
            $this->metadata = array_merge($this->metadata, $key);
        } else {
            $this->metadata[$key] = $value;
        }

        return $this;
    }

    /**
     * Get metadata value(s).
     */
    public function getMetadata(?string $key = null): mixed
    {
        if ($key === null) {
            return $this->metadata;
        }

        return $this->metadata[$key] ?? null;
    }

    /**
     * Set a custom name for the file.
     */
    public function name(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Set a custom preview URL.
     */
    public function previewUrl(string $url): static
    {
        $this->previewUrl = $url;

        return $this;
    }

    /**
     * Check if this file exists on the disk.
     */
    public function exists(): bool
    {
        if ($this->media) {
            return true;
        }

        return Storage::disk($this->disk)->exists($this->path);
    }

    /**
     * Check if this file doesn't exist on the disk.
     */
    public function doesntExist(): bool
    {
        return ! $this->exists();
    }

    /**
     * Get the disk name.
     */
    public function getDisk(): string
    {
        return $this->disk;
    }

    /**
     * Get the file path.
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * Get the file name.
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Get the preview URL.
     */
    public function getPreviewUrl(): ?string
    {
        return $this->previewUrl;
    }

    /**
     * Get the file size.
     */
    public function getSize(): ?int
    {
        return $this->size;
    }

    /**
     * Get the MIME type.
     */
    public function getMimeType(): ?string
    {
        return $this->mimeType;
    }

    /**
     * Get the Media instance if from Media Library.
     */
    public function getMedia(): ?Media
    {
        return $this->media;
    }

    /**
     * Delete the file from storage.
     */
    public function delete(): bool
    {
        if ($this->media) {
            $this->media->delete();

            return true;
        }

        return Storage::disk($this->disk)->delete($this->path);
    }

    /**
     * Convert to array.
     */
    public function toArray(): array
    {
        return [
            'disk' => $this->disk,
            'path' => $this->path,
            'name' => $this->name,
            'preview_url' => $this->previewUrl,
            'size' => $this->size,
            'mime_type' => $this->mimeType,
            'metadata' => $this->metadata,
            'media_id' => $this->media?->id,
        ];
    }

    /**
     * Convert to JSON.
     */
    public function toJson($options = 0): string
    {
        return json_encode($this->jsonSerialize(), $options);
    }

    /**
     * Get data for JSON serialization.
     */
    public function jsonSerialize(): array
    {
        // Encrypt sensitive data before sending to frontend
        return [
            'source' => Crypt::encryptString(json_encode([
                'disk' => $this->disk,
                'path' => $this->path,
                'media_id' => $this->media?->id,
            ])),
            'name' => $this->name,
            'preview_url' => $this->previewUrl,
            'size' => $this->size,
            'mime_type' => $this->mimeType,
            'metadata' => $this->encryptMetadata(),
        ];
    }

    /**
     * Encrypt metadata (including Eloquent models).
     */
    protected function encryptMetadata(): array
    {
        $encrypted = [];

        foreach ($this->metadata as $key => $value) {
            if ($value instanceof \Illuminate\Database\Eloquent\Model) {
                $encrypted[$key] = Crypt::encryptString(json_encode([
                    '__model' => get_class($value),
                    '__key' => $value->getKey(),
                ]));
            } else {
                $encrypted[$key] = $value;
            }
        }

        return $encrypted;
    }

    /**
     * Restore an ExistingFile from encrypted frontend data.
     */
    public static function fromEncrypted(string $encryptedSource): ?static
    {
        try {
            $data = json_decode(Crypt::decryptString($encryptedSource), true);

            if (isset($data['media_id']) && $data['media_id']) {
                $mediaClass = config('media-library.media_model', Media::class);
                $media = $mediaClass::find($data['media_id']);

                if ($media) {
                    return static::fromMediaLibrary($media);
                }
            }

            if (isset($data['disk'], $data['path'])) {
                return static::fromDisk($data['disk'])->get($data['path']);
            }
        } catch (\Exception) {
            // @mago-expect no-empty-catch-clause: Invalid encrypted data should return null silently
            return null;
        }

        return null;
    }
}
