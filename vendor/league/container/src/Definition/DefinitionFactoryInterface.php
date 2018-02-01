<?php

namespace TypistTech\WPCFG\Vendor\League\Container\Definition;

use TypistTech\WPCFG\Vendor\League\Container\ImmutableContainerAwareInterface;

interface DefinitionFactoryInterface extends ImmutableContainerAwareInterface
{
    /**
     * Return a definition based on type of concrete.
     *
     * @param  string $alias
     * @param  mixed  $concrete
     * @return mixed
     */
    public function getDefinition($alias, $concrete);
}
