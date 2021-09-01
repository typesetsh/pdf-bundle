<?php

declare(strict_types=1);

namespace Typesetsh\PdfBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

class TypesetshPdfExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new XmlFileLoader($container, new FileLocator(dirname(__DIR__).'/Resources/config'));
        $loader->load('services.xml');

        $this->loadConfiguration($configs, $container);

        $schemes = $this->findUriResolverSchemes($container);

        $definition = $container->getDefinition('typesetsh.pdf');
        $definition->setArgument(0, $schemes);
    }

    private function loadConfiguration(array $configs, ContainerBuilder $container): void
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        if (!$container->hasParameter('typesetsh_pdf.base_dir')) {
            $container->setParameter('typesetsh_pdf.base_dir', (string)$config['base_dir']);
        }
        if (!$container->hasParameter('typesetsh_pdf.pdf_version')) {
            $container->setParameter('typesetsh_pdf.pdf_version', (string)$config['pdf_version']);
        }
        if (!$container->hasParameter('typesetsh_pdf.allowed_directories')) {
            $container->setParameter('typesetsh_pdf.allowed_directories', (array)$config['allowed_directories']);
        }
        if (!$container->hasParameter('typesetsh_pdf.http.cache_dir')) {
            $container->setParameter('typesetsh_pdf.http.cache_dir', (string)$config['http']['cache_dir']);
        }
        if (!$container->hasParameter('typesetsh_pdf.http.timeout')) {
            $container->setParameter('typesetsh_pdf.http.timeout', (int)$config['http']['timeout']);
        }
        if (!$container->hasParameter('typesetsh_pdf.http.download_limit')) {
            $container->setParameter('typesetsh_pdf.http.download_limit', (int)$config['http']['download_limit']);
        }
    }

    /**
     * @return array<string, Reference>
     */
    private function findUriResolverSchemes(ContainerBuilder $container): array
    {
        $schemes = [];

        $taggedIds = $container->findTaggedServiceIds('typesetsh.uri_resolver.scheme');
        foreach ($taggedIds as $id => $tags) {
            foreach ($tags as $attributes) {
                if (isset($attributes["scheme"])) {
                    $schemes[$attributes["scheme"]] = new Reference($id);
                }
            }
        }

        return $schemes;
    }
}
