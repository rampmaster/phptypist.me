<?php

declare(strict_types=1);

namespace Rampmaster\PHPTypistMe\Renderer;

use Rampmaster\PHPTypistMe\Model\Chapter;

interface RendererInterface
{
    public function setDebug(bool $debug): void;
    public function setBasePath(string $path): void;
    public function setTitle(string $title): void;
    public function setAuthor(string $author): void;
    public function setStyle(array $options): void;
    public function setCover(array $options): void;
    public function setTOC(array $options): void;
    public function setChapter(Chapter $chapter): void;
    public function setHeader(array $options): void;
    public function setFooter(array $options): void;
    public function render(): mixed;
}
