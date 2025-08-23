<?php

declare(strict_types=1);

namespace Rampmaster\PHPTypistMe\Configuration;

use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Yaml\Yaml;

class ConfigurationLoader
{
    public array $config = [];

    public function load(array $configs): void
    {
        /*
        $config = Yaml::parse(
            file_get_contents(__DIR__ . '/src/Matthias/config/config.yaml')
        );
        $extraConfig = Yaml::parse(
            file_get_contents(__DIR__ . '/src/Matthias/config/config_extra.yaml')
        );

        $configs = [$config, $extraConfig];
        */

        $processor = new Processor();
        $databaseConfiguration = new Configuration();
        $this->config = $processor->processConfiguration(
            $databaseConfiguration,
            $configs
        );
    }

    public function getConfig(string $name): mixed
    {
        return $this->config[$name] ?? false;
    }
}
