<?php

declare(strict_types=1);

namespace Rampmaster\PHPTypistMe\Configuration;

use Rampmaster\PHPTypistMe\Exception\NotReloadConfigException;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Yaml\Yaml;

class ConfigurationLoader
{
    private array $config = [];

    private array $data = [];

    private bool $isProcess = false;

    public function process(): void
    {
        $processor = new Processor();
        $databaseConfiguration = new Configuration();
        $this->data = $processor->processConfiguration(
            $databaseConfiguration,
            $this->config
        );
        $this->isProcess = true;
    }

    public function addArrayConfig(array $array): void
    {
        $this->checkAllowConfigLoad();
        $this->config[] = $array;
    }

    public function addYamlConfig(string $path): void
    {
        $this->checkAllowConfigLoad();
        $this->config[] = Yaml::parse(
            file_get_contents($path)
        );
    }

    public function getConfig(string $name): mixed
    {
        $this->checkConfigProcess();
        return $this->data[$name] ?? false;
    }

    public function getAllConfig(): array
    {
        $this->checkConfigProcess();
        return $this->data;
    }

    private function checkConfigProcess(): void
    {
        if (!$this->isProcess) {
            $this->process();
        }
    }

    private function checkAllowConfigLoad(): void
    {
        if ($this->isProcess) {
            throw new NotReloadConfigException('Disallow reload config');
        }
    }
}
