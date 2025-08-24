<?php

declare(strict_types=1);

namespace Rampmaster\PHPTypistMe\Contracts;

use League\CommonMark\Environment\Environment;
use Rampmaster\PHPTypistMe\Renderer\RendererInterface;

interface Observer
{
    public function parsed(Chapter $chapter): void;

    public function initializedMarkdownEnvironment(Environment $environment): void;

    public function initializedPdf(RendererInterface $pdfRenderer): void;

    public function coverAdded(RendererInterface $pdfRenderer): void;
}
