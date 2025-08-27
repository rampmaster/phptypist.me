<?php

declare(strict_types=1);

namespace Rampmaster\PHPTypistMe\EventListener;

use DOMDocument;
use DOMElement;
use Rampmaster\PHPTypistMe\Event\ChapterEvent;
use Rampmaster\PHPTypistMe\Model\Chapter;

class FirstElementInChapterCSSClassListener
{
    public function __construct(protected string $class = 'chapter-beginning', protected bool $skipFirst = true)
    {
    }

    public function parsed(ChapterEvent $event): void
    {
        $chapter = $event->getChapter();
        if ($this->skipFirst === false || !$chapter->isFirstChapter()) {
            $dom = $this->getDomDocument($chapter);

            /** @var DOMElement $firstElement */
            $firstElement = $dom->firstChild;

            $classes = array_filter(explode(' ', $firstElement->getAttribute('class')));
            $classes[] = $this->class;
            $firstElement->setAttribute('class', implode(' ', array_unique($classes)));

            $chapter->setHtml($dom->saveHTML());
        }
    }

    protected function getDomDocument(Chapter $chapter): DOMDocument
    {
        $originalDom = new DOMDocument('1.0', 'UTF-8');

        // not doing html/body non-implied because that causes parsing errors in some contexts
        $originalDom->loadHTML($chapter->getHtml(), LIBXML_HTML_NODEFDTD);

        $resultDom = new DOMDocument('1.0', 'UTF-8');
        foreach ($originalDom->getElementsByTagName('body')->item(0)->childNodes as $node) {
            $resultDom->appendChild($resultDom->importNode($node, true));
        }

        return $resultDom;
    }
}
