<?php

declare(strict_types=1);

namespace Tempest\View;

use ReflectionClass;
use Tempest\Container\Container;
use Tempest\Discovery\Discovery;

final readonly class ViewComponentDiscovery implements Discovery
{
    private const string CACHE_PATH = __DIR__ . '/view-component-discovery.cache.php';

    public function __construct(
        private ViewConfig $viewConfig,
    ) {
    }

    public function discover(ReflectionClass $class): void
    {
        if (! $class->isInstantiable()) {
            return;
        }

        if (! $class->implementsInterface(ViewComponent::class)) {
            return;
        }

        $this->viewConfig->addViewComponent($class);
    }

    public function hasCache(): bool
    {
        return file_exists(self::CACHE_PATH);
    }

    public function storeCache(): void
    {
        file_put_contents(self::CACHE_PATH, serialize($this->viewConfig->viewComponents));
    }

    public function restoreCache(Container $container): void
    {
        $handlers = unserialize(file_get_contents(self::CACHE_PATH));

        $this->viewConfig->viewComponents = $handlers;
    }

    public function destroyCache(): void
    {
        @unlink(self::CACHE_PATH);
    }
}
