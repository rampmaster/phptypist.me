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

$typist = new Typist();
$config = new ConfigurationLoader();
$config->addArrayConfig($configData);
$config->process();
$result = $typist->generate($config);
file_put_contents(__DIR__ . '/01-basic.pdf', $result);
