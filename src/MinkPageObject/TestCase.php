<?php

namespace MinkPageObject;

use Behat\Mink\Session;
use Symfony\Component\Yaml\Yaml;

/**
 * Base Class for Page Object Mink Tests
 *
 * @author Yohan Giarelli <yohan@frequence-web.fr>
 */
class TestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Session
     */
    protected static $session;

    /**
     * @var array
     */
    protected static $config;

    /**
     * @var \Pimple
     */
    protected static $container;

    /**
     * Loads the driver (but don't run it) statically.
     */
    public static function setUpBeforeClass()
    {
        self::$config    = Yaml::parse(realpath(getcwd().'/mink-page-object.yml'));
        self::$container = new \Pimple();

        self::loadDriver();
        self::loadContainer();
    }

    /**
     * Start the session for each tests
     */
    public function setUp()
    {
        self::$session->start();
    }

    /**
     * Stops the session after test
     */
    public function tearDown()
    {
        self::$session->stop();
    }

    /**
     * @return Session
     */
    public function getSession()
    {
        return self::$session;
    }

    /**
     * @return \Behat\Mink\Element\DocumentElement
     */
    public function getPage()
    {
        return $this->getSession()->getPage();
    }

    /**
     * Returns a view
     *
     * @param  string $view
     *
     * @return View\ViewInterface
     */
    public function get($view)
    {
        return self::$container[$view];
    }

    /**
     * Loads the driver and Mink Session
     */
    protected static function loadDriver()
    {
        $loaderClass  = sprintf('%s\Loader\%sLoader', __NAMESPACE__, self::$config['driver']['name']);
        $loader = new $loaderClass;

        self::$session = new Session($loader->load(self::$config['driver']));
    }

    /**
     * Loads the DIC and inject views
     */
    private static function loadContainer()
    {
        $session = self::$session;
        $baseUrl = self::$config['baseUrl'];

        foreach (self::$config['views'] as $view => $class) {
            self::$container[$view] = self::$container->share(function($c) use ($session, $baseUrl, $class) {

                return new $class($c, $session, $baseUrl);
            });
        }
    }
}
