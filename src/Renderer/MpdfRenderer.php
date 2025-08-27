<?php

declare(strict_types=1);

namespace Rampmaster\PHPTypistMe\Renderer;

use Mpdf\HTMLParserMode;
use Mpdf\Mpdf;
use Mpdf\MpdfException;
use Rampmaster\PHPTypistMe\Exception\NotRendererException;
use Rampmaster\PHPTypistMe\Exception\PDFRendererException;
use Rampmaster\PHPTypistMe\Model\Chapter;

/**
 * @throws PDFRendererException
 * @throws MpdfException
 * @throws NotRendererException
 */
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

    private string $title;
    private string $author;

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
        $this->title = $title;
        $this->mpdf->SetTitle($title);
    }

    public function setAuthor(string $author): void
    {
        $this->author = $author;
        $this->mpdf->SetAuthor($author);
    }

    /**
     * @throws PDFRendererException
     */
    public function setStyle(array $options): void
    {
        if (!$options['enabled']) {
            return;
        }

        $content = $options['content'];

        switch ($options['type']) {
            case 'css':
                $this->writeHtml(file_get_contents($content), HTMLParserMode::HEADER_CSS);
                break;
            case 'html':
                $this->writeHtml(file_get_contents($content));
                break;
            default:
                throw new PDFRendererException('Unexpected value');
        }
    }

    public function setCover(array $options): void
    {
        if (!$options['enabled']) {
            return;
        }

        switch ($options['type']) {
            case "jpg":
                $this->mpdf->Image($options['content'], 0, 0, 210, 297, 'jpg', '', true, false);
                break;
            case "html":
                $this->writeHtml(file_get_contents($options['content']));
                break;
            default:
                $coverHtml = '<section style="text-align: center; page-break-after:always; padding-top: 100pt"><h1>%s</h1><h2>%s</h2></section>';
                $this->writeHtml(sprintf($coverHtml, $this->title, $this->author));
        }
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
        $this->writeHtml($mpdfTocDefinition);
    }

    public function setChapter(Chapter $chapter): void
    {
        $this->writeHtml($chapter->getHtml());
    }

    public function setHeader(array $options): void
    {
        if (!$options['enabled']) {
            return;
        }
        $headerDefinition = '<header class="header">' . htmlentities(string: $options['content']) . '</header>';
        $this->mpdf->SetHTMLHeader($headerDefinition);
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

    private function writeHtml(string $html, int $parseMode = HTMLParserMode::DEFAULT_MODE): void
    {
        try {
            $this->mpdf->WriteHTML($html, $parseMode);
        } catch (MpdfException $e) {
            throw new PDFRendererException(previous: $e);
        }
    }
}
