<?php

declare(strict_types=1);

namespace Rampmaster\PHPTypistMe;

use Illuminate\Support\Collection;
use League\CommonMark\Environment\Environment;
use Rampmaster\PHPTypistMe\Model\Chapter;
use Rampmaster\PHPTypistMe\Contracts\Observer;
use Rampmaster\PHPTypistMe\Renderer\RendererInterface;

class ObserverCollection extends Collection
{
    public function initializedMarkdownEnvironment(Environment $environment): self
    {
        $this->each(fn (Observer $observer) => $observer->initializedMarkdownEnvironment($environment));
        return $this;
    }

    public function initializedPdf(RendererInterface $pdfRenderer): self
    {
        $this->each(fn (Observer $observer) => $observer->initializedPdf($pdfRenderer));
        return $this;
    }

    public function coverAdded(RendererInterface $pdfRenderer): self
    {
        $this->each(fn (Observer $observer) => $observer->coverAdded($pdfRenderer));
        return $this;
    }

    public function parsed(Chapter $chapter): self
    {
        $this->each(fn (Observer $observer) => $observer->parsed($chapter));
        return $this;
    }
}
