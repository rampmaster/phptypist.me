<?php

declare(strict_types=1);

namespace Tests\Unit;

use Tests\TestCase;
use Rampmaster\PHPTypistMe\Config;
use Rampmaster\PHPTypistMe\Contracts\Chapter;
use Rampmaster\PHPTypistMe\Typist;

class FunctionalTest extends TestCase
{
    /*
    public function testGeneratedCoverNoTocNoFooterNoContentFiles(): void
    {
        $theme = __DIR__ . '/../../assets/data/theme';
        $configData =[
            'theme' => $theme,
            'title' => 'A title',
            'author' => 'An author',
        ];

        $typist = new Typist();
        $config = new \Rampmaster\PHPTypistMe\Configuration\ConfigurationLoader();
        $config->addArrayConfig($configData);
        $config->process();
        $result = $typist->generate($config);

        self::assertNotEmpty($result); // the best I can try without really digging in

        // perhaps in the future would be nice to do a mock or spy to determine the default observers ran
    }
    */
    public function testHtmlCoverWithTocNoTocHeaderWithFooterWithFiles(): void
    {
        $observerForParsed = new class extends \Rampmaster\PHPTypistMe\Observers\Observer {
            public function __construct(public int $times = 0)
            {
            }

            public function parsed(Chapter $chapter): void
            {
                $this->times++;
            }
        };

        $theme = __DIR__ . '/../../assets/data/another-theme';
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
        $config = new \Rampmaster\PHPTypistMe\Configuration\ConfigurationLoader();
        $config->addArrayConfig($configData);
        $config->process();
        $result = $typist->generate($config);

        self::assertNotEmpty($result);

        //self::assertEquals(2, $observerForParsed->times);
    }

    public function testImageCover(): void
    {
        $observerForParsed = new class extends \Rampmaster\PHPTypistMe\Observers\Observer {
            public function __construct(public int $times = 0)
            {
            }

            public function parsed(Chapter $chapter): void
            {
                $this->times++;
            }
        };

        $theme = __DIR__ . '/../../assets/data/theme';
        $content = __DIR__ . '/../../assets/data/content';
        $configData = [
            'theme' => $theme,
            'content' => [$content],
            'title' => 'The title',
            'author' => 'The author',
        ];

        $typist = new Typist();
        $config = new \Rampmaster\PHPTypistMe\Configuration\ConfigurationLoader();
        $config->addArrayConfig($configData);
        $config->process();
        $result = $typist->generate($config);

        self::assertNotEmpty($result);

        //self::assertEquals(2, $observerForParsed->times);
    }
}
