<?php

namespace MinkPageObject\Loader;

use Behat\Mink\Driver\DriverInterface;

/**
 * Classe de chargement des drivers
 *
 * @author Yohan Giarelli <yohan@frequence-web.fr>
 */
interface LoaderInterface
{
    /**
     * @param array $config
     *
     * @return DriverInterface
     */
    public function load(array $config);
}
