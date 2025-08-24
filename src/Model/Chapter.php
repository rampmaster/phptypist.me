<?php

declare(strict_types=1);

namespace Rampmaster\PHPTypistMe\Model;

use League\CommonMark\Extension\FrontMatter\Output\RenderedContentWithFrontMatter;
use League\CommonMark\Output\RenderedContent;
use League\CommonMark\Output\RenderedContentInterface;

class Chapter implements \Rampmaster\PHPTypistMe\Contracts\Chapter
{
    protected string $html;

    public function __construct(protected RenderedContent|RenderedContentInterface $markdown, protected int $chapterNumber, protected int $totalChapters)
    {
        $this->setHtml($markdown);
    }

    public function getHtml(): string
    {
        return $this->html;
    }

    public function setHtml($html): void
    {
        $this->html = (string) $html;
    }

    public function getChapterNumber(): int
    {
        return $this->chapterNumber;
    }

    public function getTotalChapters(): int
    {
        return $this->totalChapters;
    }

    public function isFirstChapter(): bool
    {
        return $this->chapterNumber === 1;
    }

    public function isLastChapter(): bool
    {
        return $this->chapterNumber === $this->totalChapters;
    }

    public function getMetaData(): array
    {
        return $this->markdown instanceof RenderedContentWithFrontMatter ? $this->markdown->getFrontMatter() : [];
    }
}
