<?php

namespace MinkPageObject\Loader;

use Behat\Mink\Driver\Selenium2Driver;

/**
 * Selenium2 Mink driver loader
 *
 * @author Yohan Giarelli <yohan@frequence-web.fr>
 */
class Selenium2Loader implements LoaderInterface
{
    /**
     * @{inheritDoc}
     */
    public function load(array $config)
    {
        $browser  = isset($config['browser'])  ? $config['browser']  : 'firefox';
        $host     = isset($config['host'])     ? $config['host']     : 'localhost';
        $port     = isset($config['port'])     ? $config['port']     : '4444';
        $protocol = isset($config['protocol']) ? $config['protocol'] : 'http';

        return new Selenium2Driver($browser, null, sprintf('%s://%s:%s/wd/hub', $protocol, $host, $port));
    }
}
