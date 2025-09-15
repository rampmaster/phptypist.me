<?php

declare(strict_types=1);

namespace Rampmaster\PHPTypistMe\Reader;

use Rampmaster\PHPTypistMe\Reader\ReaderInterface;

class PlainTextReader implements ReaderInterface
{
    public function read(string $filePath): string
    {
        return file_get_contents($filePath);
    }
}
