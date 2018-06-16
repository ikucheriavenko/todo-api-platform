<?php
/**
 * Created by PhpStorm.
 * User: Ivan Kucheriavenko
 * Date: 6/15/18
 * Time: 12:47 PM.
 */

declare(strict_types=1);

namespace App\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Class AppUserParameterPass.
 */
class AppUserParameterPass implements CompilerPassInterface
{
    /**
     * You can modify the container here before it is dumped to PHP code.
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasParameter('app.user_class')) {
            return;
        }

        $this->remapParameter($container);
    }

    /**
     * @param ContainerBuilder $container
     */
    protected function remapParameter(ContainerBuilder $container): void
    {
        $config = $container->getExtensionConfig('security');

        $class = $this->getNestedConfigValue('0.providers.default_provider.entity.class', $config);

        $container->setParameter('app.user_class', $class);
    }

    /**
     * @param string $key
     * @param array  $array
     *
     * @return array|mixed
     */
    public function getNestedConfigValue(string $key, array $array)
    {
        if (is_null($key)) {
            return $array;
        }

        if (isset($array[$key])) {
            return $array[$key];
        }

        foreach (explode('.', $key) as $segment) {
            if (!is_array($array) || !array_key_exists($segment, $array)) {
                return null;
            }

            $array = $array[$segment];
        }

        return $array;
    }
}
