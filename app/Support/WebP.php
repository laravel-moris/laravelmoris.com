<?php

declare(strict_types=1);

namespace App\Support;

use GdImage;
use RuntimeException;
use Throwable;

/**
 * WebP Converter so it is lightweight
 */
class WebP
{
    private GdImage $image;

    private int $quality = 80;

    private ?int $maxWidth = null;

    /**
     * @throws Throwable
     */
    public function __construct(string $content)
    {
        $image = @imagecreatefromstring($content);

        throw_if($image === false, RuntimeException::class, 'Failed to create image from string.');

        $this->image = $image;

        imagepalettetotruecolor($this->image);
        imagealphablending($this->image, false);
        imagesavealpha($this->image, true);
    }

    /**
     * @throws Throwable
     */
    public static function make(string $content): self
    {
        return new self($content);
    }

    public function maxWidth(int $width): self
    {
        $this->maxWidth = $width;

        return $this;
    }

    public function quality(int $quality): self
    {
        $this->quality = $quality;

        return $this;
    }

    public function toBinary(): string
    {
        $image = $this->image;

        if ($this->maxWidth !== null && imagesx($image) > $this->maxWidth) {
            $scaled = imagescale($image, $this->maxWidth);

            if ($scaled !== false) {
                $image = $scaled;
            }
        }

        ob_start();
        imagewebp($image, null, $this->quality);
        $content = ob_get_clean();

        if ($image !== $this->image) {
            imagedestroy($image);
        }

        return (string) $content;
    }

    public function __destruct()
    {
        imagedestroy($this->image);
    }
}
