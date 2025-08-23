<?php

use Rampmaster\PHPTypistMe\Config;
use Rampmaster\PHPTypistMe\Typist;

require __DIR__ . '/../../vendor/autoload.php';

$theme = __DIR__ . '/../../assets/data/theme';
$content = __DIR__ . '/../../assets/data/content';
$config = new Config([
    'theme' => $theme,
    'content' => $content,
    'title' => 'A title',
    'author' => 'An author',
]);

$typist = new Typist();
$config = new \Rampmaster\PHPTypistMe\Configuration\ConfigurationLoader();
$config->load(['typist_me' => [
    'theme' => $theme,
    'content' => [
        $content
    ],
    'title' => 'A title',
    'author' => 'An author',
]]);
$result = $typist->generate($config->config);
file_put_contents(__DIR__.'/01-basic.pdf',$result);