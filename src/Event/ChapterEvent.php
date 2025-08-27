<?php

declare(strict_types=1);

namespace Rampmaster\PHPTypistMe\Event;

use Rampmaster\PHPTypistMe\Model\Chapter;
use Symfony\Contracts\EventDispatcher\Event;

class ChapterEvent extends Event
{
    public function __construct(private Chapter $chapter)
    {
    }

    public function getChapter()
    {
        return $this->chapter;
    }

    public function setChapter(Chapter $chapter)
    {
        $this->chapter = $chapter;
    }
}
