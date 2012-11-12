<?php

namespace MinkPageObject\View;

use Behat\Mink\Session;
use Behat\Mink\Element\DocumentElement;
use Behat\Mink\Element\NodeElement;

/**
 * Vue de base
 *
 * @author Yohan Giarelli <yohan@frequence-web.fr>
 */
class View
{
    /**
     * @var Session
     */
    protected $session;

    /**
     * @var DocumentElement
     */
    protected $page;

    /**
     * @var string
     */
    protected $root;

    /**
     * @var NodeElement
     */
    protected $rootElement;

    /**
     * @var array
     */
    protected $parts = array();

    /**
     * @var array<ViewInterface>
     */
    protected $children = array();

    /**
     * @var boolean
     */
    protected $inited = false;

    /**
     * @var View
     */
    protected $parent = null;

    /**
     * @var \Pimple
     */
    protected $container;

    /**
     * @var int
     */
    protected $index;

    /**
     * @param \Pimple $container
     * @param Session $session
     * @param View    $parent
     */
    public function __construct(\Pimple $container, Session $session, $parent = null)
    {
        $this->container = $container;
        $this->session   = $session;
        if ($parent instanceof View) {
            $this->parent = $parent;
        }
    }

    /**
     * @return View
     */
    public function init()
    {
        $this->page = $this->session->getPage();

        if (null !== $this->index) {
            $this->rootElement = $this->page->findAll('css', $this->root);
            $this->rootElement = $this->rootElement[$this->index];
        } else {
            $this->rootElement = $this->page->find('css', $this->root);
        }

        $this->inited = true;

        return $this;
    }

    /**
     * @return \Behat\Mink\Element\DocumentElement
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * @param string $root
     *
     * @return View
     */
    public function setRoot($root)
    {
        $this->root = $root;

        return $this;
    }

    /**
     * @return string
     */
    public function getRoot()
    {
        return $this->root;
    }

    /**
     * @param string $name
     * @param string $locator
     * @param string $type
     *
     * @return View
     */
    public function add($name, $locator, $type = 'css')
    {
        $this->parts[$name] = array($type, $locator);

        return $this;
    }

    /**
     * @param string $name
     * @param string $root
     *
     * @return View
     */
    public function addChild($name, $root)
    {
        $view = new self($this->container, $this->session, $this);
        $view->setRoot($root);

        $this->children[$name] = $view;

        return $view;
    }

    /**
     * @return View
     */
    public function end()
    {
        return $this->parent;
    }

    /**
     * @param string      $name
     *
     * @return NodeElement
     */
    public function get($name)
    {
        if (!$this->inited) {
            $this->init();
        }

        return call_user_func_array(array($this->getPage(), 'find'), $this->parts[$name]);
    }

    /**
     * @param $name
     *
     * @return array<NodeElement>
     */
    public function all($name)
    {
        if (!$this->inited) {
            $this->init();
        }

        return call_user_func_array(array($this->getPage(), 'findAll'), $this->parts[$name]);
    }

    /**
     * @param string $name
     *
     * @return View
     */
    public function getChild($name)
    {
        return $this->children[$name];
    }

    /**
     * @param string $name
     * @param int    $index
     *
     * @return View
     */
    public function getChildByIndex($name, $index)
    {
        $child = clone $this->children[$name];
        $child->index = $index;
        $child->init();

        return $child;
    }

    /**
     * @param string $name
     *
     * @return View
     */
    public function getShared($name)
    {
        $view = $this->container[$name];
        $view->parent = $this;
        $view->init();

        return $view;
    }

    public function waitFor($main, $timeout = 20000)
    {
        $selector = $this->parts[$main][1];

        $this->session->wait($timeout, sprintf('$("%s:visible").length > 0', $selector));

        return $this;
    }
}
