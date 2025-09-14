<?php

declare(strict_types=1);

namespace Rampmaster\PHPTypistMe\Writer;

interface WriterInterface
{
    public function write(string $content): void;
}
