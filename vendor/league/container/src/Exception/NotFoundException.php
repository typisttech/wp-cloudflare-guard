<?php

namespace TypistTech\WPCFG\Vendor\League\Container\Exception;

use TypistTech\WPCFG\Vendor\Interop\Container\Exception\NotFoundException as NotFoundExceptionInterface;
use InvalidArgumentException;

class NotFoundException extends InvalidArgumentException implements NotFoundExceptionInterface
{
}
