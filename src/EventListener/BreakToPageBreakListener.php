<?php

declare(strict_types=1);

namespace Rampmaster\PHPTypistMe\EventListener;

use Rampmaster\PHPTypistMe\Event\ChapterEvent;

class BreakToPageBreakListener
{
    public function __construct(protected string $token = '{BREAK}')
    {
    }

    public function parsed(ChapterEvent $event): void
    {
        $chapter = $event->getChapter();
        $breakHtml = '<div style="page-break-after:always"></div>';

        $chapter->setHtml(str_replace($this->token, $breakHtml, $chapter->getHtml()));
    }
}
