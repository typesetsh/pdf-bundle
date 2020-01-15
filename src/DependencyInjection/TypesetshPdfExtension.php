<?php

declare(strict_types=1);

namespace Typesetsh\PdfBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

class TypesetshPdfExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new XmlFileLoader($container, new FileLocator(dirname(__DIR__).'/Resources/config'));
        $loader->load('services.xml');

        $definition = $container->getDefinition('typesetsh.pdf');
        $definition->replaceArgument(0, $config['http']['cache_dir']);
        $definition->setProperty('baseUri', $config['base_dir']);
        $definition->setProperty('allowedDirectories', $config['allowed_directories']);
        $definition->setProperty('allowHttp', $config['http']['allow']);
        $definition->setProperty('downloadLimit', $config['http']['download_limit']);
        $definition->setProperty('timeout', $config['http']['timeout']);
    }
}
