<?php

declare(strict_types=1);

namespace Rampmaster\PHPTypistMe\Observers;

use Rampmaster\PHPTypistMe\Contracts\Chapter;

class Credits extends Observer
{
    public function __construct(protected string $class = 'credits-box')
    {
    }

    public function parsed(Chapter $chapter): void
    {
        if ($chapter->isLastChapter()) {
            $creditsHtml = sprintf(
                '<div class="%s">Created using <a href="https:://typesetter.io">Typist.io</a></div>',
                $this->class,
            );

            $chapter->setHtml($chapter->getHtml() . $creditsHtml);
        }
    }
}
