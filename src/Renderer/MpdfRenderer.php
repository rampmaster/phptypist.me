<?php

declare(strict_types=1);

namespace Rampmaster\PHPTypistMe\Renderer;

use Mpdf\Mpdf;
use Mpdf\MpdfException;
use Rampmaster\PHPTypistMe\Exception\NotRendererException;
use Rampmaster\PHPTypistMe\Model\Chapter;

class MpdfRenderer implements RendererInterface
{
    public const DEFAULT_SETTINGS = [
        'mode' => 'utf-8',
        'margin_left' => 27,
        'margin_right' => 27,
        'margin_bottom' => 14,
        'margin_top' => 14,
    ];

    private Mpdf $mpdf;

    /**
     * @throws MpdfException
     * @throws NotRendererException
     */
    public function __construct($settings = self::DEFAULT_SETTINGS)
    {
        if (!class_exists(Mpdf::class)) {
            throw new NotRendererException('is required before execute "composer require mpdf/mpdf"');
        }
        $this->mpdf = new Mpdf($settings);
    }

    public function setDebug(bool $debug): void
    {
        $this->mpdf->showImageErrors = $debug;
    }

    public function setBasePath(string $path): void
    {
        $this->mpdf->basepath = $path;
    }

    public function setTitle(string $title): void
    {
        $this->mpdf->SetTitle($title);
    }

    public function setAuthor(string $author): void
    {
        $this->mpdf->SetAuthor($author);
    }

    public function setTOC(array $options): void
    {
        if (!$options['enabled']) {
            return;
        }

        $tocLevels = ['H1' => 0, 'H2' => 1];
        $tocLinks = $options['links'];
        $tocHeader = $options['header'];
        $this->mpdf->h2toc = $tocLevels;
        $this->mpdf->h2bookmarks = $tocLevels;

        $mpdfTocDefinition = '<tocpagebreak toc-bookmarkText="Contents" toc-page-selector="toc-page"';
        $mpdfTocDefinition .= sprintf(' links="%s"', $tocLinks ? 'on' : 'off');
        if ($tocHeader) {
            $mpdfTocDefinition .= sprintf(' toc-preHTML="%s"', htmlentities('<h1 id="toc-header">' . $tocHeader . '</h1>'));
        }
        $mpdfTocDefinition .= '>';
        $this->mpdf->WriteHTML($mpdfTocDefinition);
    }

    public function setChapter(Chapter $chapter): void
    {
        $this->mpdf->WriteHTML($chapter->getHtml());
    }

    public function setFooter(array $options): void
    {
        if (!$options['enabled']) {
            return;
        }
        $footerDefinition = '<footer class="footer">' . htmlentities(string: $options['content']) . '</footer>';
        $this->mpdf->SetHTMLFooter($footerDefinition);
    }

    public function render(): mixed
    {
        return $this->mpdf->OutputBinaryData();
    }
}
