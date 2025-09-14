<?php

declare(strict_types=1);

namespace Rampmaster\PHPTypistMe\Reader;

interface ReaderInterface
{
    public function read(string $source): string;
}
