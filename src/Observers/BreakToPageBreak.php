<?php

declare(strict_types=1);

namespace Rampmaster\PHPTypistMe\Observers;

use Rampmaster\PHPTypistMe\Contracts\Chapter;

class BreakToPageBreak extends Observer
{
    public function __construct(protected string $token = '{BREAK}')
    {
    }

    public function parsed(Chapter $chapter): void
    {
        $breakHtml = '<div style="page-break-after:always"></div>';

        $chapter->setHtml(str_replace($this->token, $breakHtml, $chapter->getHtml()));
    }
}
