<?php

declare(strict_types=1);

namespace Rampmaster\PHPTypistMe\Model;

class Book
{
    private string $title;
    private string $author;
    private string $language;
    /** @var Chapter[] */
    private array $chapters = [];

    public function __construct(string $title, string $author, string $language = 'en')
    {
        $this->title = $title;
        $this->author = $author;
        $this->language = $language;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getAuthor(): string
    {
        return $this->author;
    }

    public function getLanguage(): string
    {
        return $this->language;
    }

    /**
     * @return Chapter[]
     */
    public function getChapters(): array
    {
        return $this->chapters;
    }

    public function addChapter(Chapter $chapter): void
    {
        $this->chapters[] = $chapter;
    }
}
