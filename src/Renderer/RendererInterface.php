<?php

declare(strict_types=1);

namespace Rampmaster\PHPTypistMe\Renderer;

interface RendererInterface
{
    public function setTitle(string $title): void;
    public function setAuthor(string $author): void;
}
