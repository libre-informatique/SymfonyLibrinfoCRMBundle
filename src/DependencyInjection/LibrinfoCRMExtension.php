<?php

/*
 * This file is part of the Blast Project package.
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Librinfo\CRMBundle\DependencyInjection;

use Blast\CoreBundle\DependencyInjection\BlastCoreExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\FileLoader;
use Symfony\Component\Yaml\Yaml;

/**
 * This is the class that loads and manages your bundle configuration.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class LibrinfoCRMExtension extends BlastCoreExtension implements PrependExtensionInterface
{
    /**
     * {@inheritdoc}
     */
    public function prepend(ContainerBuilder $container)
    {
        // Define Application Circles by parsing circles.yml files in installed Bundles
        $circles = [];
        $bundles = $container->getParameter('kernel.bundles');

        foreach ($bundles as $bundleClassName) {
            $rc = new \ReflectionClass($bundleClassName);

            $bundleDir = dirname($rc->getFileName());
            $circlesYml = $bundleDir . '/Resources/config/circles.yml';
            if (file_exists($circlesYml)) {
                $circles = array_merge($circles, Yaml::parse(file_get_contents($circlesYml)));
            }
        }

        if ($circles) {
            $container->prependExtensionConfig('librinfo_crm', ['Circle' => ['app_circles' => $circles]]);
            $container->prependExtensionConfig('twig', ['globals' => ['librinfo_app_circles' => $circles]]);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function loadSecurity(ContainerBuilder $container)
    {
        if (class_exists('\Librinfo\SecurityBundle\Configurator\SecurityConfigurator')) {
            \Librinfo\SecurityBundle\Configurator\SecurityConfigurator::getInstance($container)->loadSecurityYml(__DIR__ . '/../Resources/config/security.yml');
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function loadDataFixtures(ContainerBuilder $container, FileLoader $loader)
    {
        // the fixtures
        if ($container->getParameter('kernel.environment') == 'test') {
            $loader->load('datafixtures.yml');
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function loadCodeGenerators(ContainerBuilder $container, array $config)
    {
        // Entity code generators
        $container->setParameter('librinfo_crm', $config);
        $container->setParameter('librinfo_crm.code_generator.supplier',
            $container->getParameter('librinfo_crm')['code_generator']['supplier']
        );
        $container->setParameter('librinfo_crm.code_generator.customer',
            $container->getParameter('librinfo_crm')['code_generator']['customer']
        );

        return $this;
    }
}
