<?php

namespace WPCFG;

use WPCFG\Vendor\League\Container\Container;

/**
 * Inherited Methods
 *
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = null)
 *
 * @SuppressWarnings(PHPMD)
 */
class UnitTester extends \Codeception\Actor
{
    use _generated\UnitTesterActions;

    /**
     * @var Container
     */
    private $container;

    /**
     * Define custom actions here
     */
    public function getContainer(): Container
    {
        $wpcfg = new WPCFG;
        $this->container = $wpcfg->getContainer();
        return $this->container;
    }
}
