<?php

declare(strict_types=1);

namespace Rampmaster\PHPTypistMe;

use League\CommonMark\Environment\Environment;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;
use League\CommonMark\Extension\GithubFlavoredMarkdownExtension;
use League\CommonMark\MarkdownConverter;
use Symfony\Component\Finder\Finder;
use Rampmaster\PHPTypistMe\Model\Chapter;
use Rampmaster\PHPTypistMe\Renderer\MpdfRenderer;

class Typist
{
    protected array $listeners = [];

    public function generate(array $bookConfig): string
    {
        var_dump($bookConfig);

        $config = [];
        $environment = new Environment($config);
        $environment->addExtension(new CommonMarkCoreExtension());
        $environment->addExtension(new GithubFlavoredMarkdownExtension());

        //$bookConfig->observers->initializedMarkdownEnvironment($environment);
        $converter = new MarkdownConverter($environment);

        // TODO: Abstract to factory
        $pdfRenderer = new MpdfRenderer();

        $pdfRenderer->setDebug(true);
        $pdfRenderer->setBasePath($bookConfig['content'][0] . '/');

        $pdfRenderer->setTitle($bookConfig['title']);
        $pdfRenderer->setAuthor($bookConfig['author']);

        //$bookConfig->observers->initializedPdf($pdfRenderer);

        // TODO: CSS reader
        $stylesheet = $bookConfig['theme'] . '/theme.html';
        //$mpdf->WriteHTML(file_get_contents($stylesheet));

        //TODO: Cover reader
        /*
        if (is_readable($bookConfig->theme . '/cover.jpg')) {
            $mpdf->Image($bookConfig->theme . '/cover.jpg', 0, 0, 210, 297, 'jpg', '', true, false);
        } elseif (is_readable($bookConfig->theme . '/cover.html')) {
            $mpdf->WriteHTML(file_get_contents($bookConfig->theme . '/cover.html'));
        } else {
            $coverHtml = '<section style="text-align: center; page-break-after:always; padding-top: 100pt"><h1>%s</h1><h2>%s</h2></section>';
            $mpdf->WriteHTML(sprintf($coverHtml, $bookConfig->title, $bookConfig->author));
        }
        */

        //$bookConfig->observers->coverAdded($pdfRenderer);

        $pdfRenderer->setTOC($bookConfig['toc']);

        $pdfRenderer->setFooter($bookConfig['footer']);

        $finder = new Finder();
        $finder->files()->in($bookConfig['content'])->name($bookConfig['markdownExtensions']);

        $totalChapters = $finder->count();
        $chapterNumber = 0;
        foreach ($finder as $contentFile) {
            $chapterNumber++;

            $markdown = file_get_contents($contentFile->getPathname());
            $chapter = new Chapter(
                markdown: $converter->convert($markdown),
                chapterNumber: $chapterNumber,
                totalChapters: $totalChapters
            );

            //$bookConfig->observers->parsed($chapter);

            $pdfRenderer->setChapter($chapter);
        }

        return $pdfRenderer->render();
    }
}
