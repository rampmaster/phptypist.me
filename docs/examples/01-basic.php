<?php

declare(strict_types=1);

use Rampmaster\PHPTypistMe\Configuration\ConfigurationLoader;
use Rampmaster\PHPTypistMe\Typist;

require __DIR__ . '/../../vendor/autoload.php';

$theme = __DIR__ . '/../../assets/data/theme';
$content = __DIR__ . '/../../assets/data/content';
$configData = [
    'theme' => $theme,
    'content' => [
        $content
    ],
    'title' => 'A title',
    'author' => 'An author',
];

$config = new ConfigurationLoader();
$config->addArrayConfig($configData);
$config->process();

$typist = new Typist();
$typist->addListener(\Rampmaster\PHPTypistMe\Event\ChapterEvent::class, [
    new \Rampmaster\PHPTypistMe\EventListener\FirstElementInChapterCSSClassListener(), 'parsed',
], 0);
$typist->addListener(\Rampmaster\PHPTypistMe\Event\ChapterEvent::class, [
    new \Rampmaster\PHPTypistMe\EventListener\BreakToPageBreakListener(), 'parsed',
], 0);
$result = $typist->generate($config);
file_put_contents(__DIR__ . '/01-basic.pdf', $result);
