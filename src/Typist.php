<?php

declare(strict_types=1);

namespace Rampmaster\PHPTypistMe;

use League\CommonMark\Environment\Environment;
use League\CommonMark\Exception\CommonMarkException;
use League\CommonMark\Extension\Attributes\AttributesExtension;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;
use League\CommonMark\Extension\CommonMark\Node\Block\FencedCode;
use League\CommonMark\Extension\CommonMark\Node\Block\IndentedCode;
use League\CommonMark\Extension\GithubFlavoredMarkdownExtension;
use League\CommonMark\MarkdownConverter;
use Rampmaster\PHPTypistMe\Configuration\ConfigurationLoader;
use Rampmaster\PHPTypistMe\Event\ChapterEvent;
use Rampmaster\PHPTypistMe\Renderer\RendererInterface;
use Spatie\CommonMarkHighlighter\FencedCodeRenderer;
use Spatie\CommonMarkHighlighter\IndentedCodeRenderer;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Finder\Finder;
use Rampmaster\PHPTypistMe\Model\Chapter;
use Rampmaster\PHPTypistMe\Renderer\MpdfRenderer;

class Typist
{
    private EventDispatcher $dispatcher;
    private ?RendererInterface $renderer = null;

    public function __construct()
    {
        $this->dispatcher = new EventDispatcher();
    }

    public function setRenderer(string $className = MpdfRenderer::class, bool $isDebug = false): void
    {
        $this->renderer = new $className();
        $this->renderer->setDebug($isDebug);
    }

    /**
     * @throws CommonMarkException
     */
    public function generate(ConfigurationLoader $bookConfig): string
    {
        $config = [];
        $environment = new Environment($config);
        $environment->addExtension(new CommonMarkCoreExtension());
        $environment->addExtension(new GithubFlavoredMarkdownExtension());
        $environment->addRenderer(FencedCode::class, new FencedCodeRenderer());
        $environment->addRenderer(IndentedCode::class, new IndentedCodeRenderer());
        $environment->addExtension(new AttributesExtension());

        $converter = new MarkdownConverter($environment);

        if (!($this->renderer instanceof RendererInterface)) {
            $this->setRenderer();
        }

        $assets = $bookConfig->getConfig('assets');
        if ($assets) {
            $this->renderer->setBasePath($assets);
        }

        $this->renderer->setTitle($bookConfig->getConfig('title'));
        $this->renderer->setAuthor($bookConfig->getConfig('author'));
        $this->renderer->setStyle($bookConfig->getConfig('style'));
        $this->renderer->setCover($bookConfig->getConfig('cover'));
        $this->renderer->setTOC($bookConfig->getConfig('toc'));
        $this->renderer->setHeader($bookConfig->getConfig('header'));
        $this->renderer->setFooter($bookConfig->getConfig('footer'));

        $finder = new Finder();
        $finder->files()->in($bookConfig->getConfig('content'))->name($bookConfig->getConfig('extension'))->sortByName();

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

            $event = new ChapterEvent($chapter);
            $this->dispatcher->dispatch($event);

            $this->renderer->setChapter($chapter);
        }

        return $this->renderer->render();
    }

    public function addListener($event, $listener, int $priority = 0): void
    {
        $this->dispatcher->addListener($event, $listener, $priority);
    }

    public function addSubscriber(EventSubscriberInterface $eventSubscriber): void
    {
        $this->dispatcher->addSubscriber($eventSubscriber);
    }
}
