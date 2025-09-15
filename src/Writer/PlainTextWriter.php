<?php

declare(strict_types=1);

namespace Rampmaster\PHPTypistMe\Writer;

use Rampmaster\PHPTypistMe\Model\Book;

class PlainTextWriter implements WriterInterface
{
    public function __construct()
    {
    }

    public function write(string $content): void
    {
        foreach ($this->book->getChapters() as $chapter) {
            $content .= $chapter->getContent() . "\n\n";
        }
        echo $content;
    }
}
