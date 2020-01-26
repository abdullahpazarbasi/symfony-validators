<?php

namespace SfV\Tests;

use const PHP_VERSION_ID;
use function dirname;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Bundle\MonologBundle\MonologBundle;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;
use Symfony\Component\Routing\RouteCollectionBuilder;

/**
 * Class Kernel
 */
class Kernel extends BaseKernel
{

    use MicroKernelTrait;

    private const CONFIG_EXTS = '.{php,xml,yaml,yml}';

    /**
     * @inheritDoc
     */
    public function registerBundles(): iterable
    {
        return [
            new FrameworkBundle(),
            new MonologBundle(),
        ];
    }

    /**
     * @return string
     */
    public function getProjectDir(): string
    {
        return dirname(__DIR__);
    }

    /**
     * @inheritDoc
     */
    protected function configureRoutes(RouteCollectionBuilder $routes)
    {
        $projectDir = $this->getProjectDir();
        $confDir = $projectDir . '/tests/Resources/config';

        // Route Registrations
        $routes->import($confDir . '/{routes}/' . $this->environment . '/*' . self::CONFIG_EXTS, '/', 'glob');
        $routes->import($confDir . '/{routes}/*' . self::CONFIG_EXTS, '/', 'glob');
        $routes->import($confDir . '/{routes}' . self::CONFIG_EXTS, '/', 'glob');
    }

    /**
     * @inheritDoc
     */
    protected function configureContainer(ContainerBuilder $cb, LoaderInterface $loader)
    {
        $projectDir = $this->getProjectDir();
        $cb->setParameter('container.dumper.inline_class_loader', PHP_VERSION_ID < 70400 || $this->debug);
        $cb->setParameter('container.dumper.inline_factories', true);
        $confDir = $projectDir . '/tests/Resources/config';

        // Package Configurations
        $loader->load($confDir . '/{packages}/*' . self::CONFIG_EXTS, 'glob');
        $loader->load($confDir . '/{packages}/' . $this->environment . '/*' . self::CONFIG_EXTS, 'glob');

        // Service Registrations
        $loader->load($confDir . '/{services}' . self::CONFIG_EXTS, 'glob');
        $loader->load($confDir . '/{services}_' . $this->environment . self::CONFIG_EXTS, 'glob');
    }

}
