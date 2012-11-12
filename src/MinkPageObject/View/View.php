<?php

namespace MinkPageObject\View;

use Behat\Mink\Session;
use Behat\Mink\Element\DocumentElement;
use Behat\Mink\Element\NodeElement;

/**
 * Represents a View
 *
 * @author Yohan Giarelli <yohan@frequence-web.fr>
 */
class View implements ViewInterface
{
    /**
     * The Mink Session
     *
     * @var Session
     */
    protected $session;

    /**
     * @var DocumentElement
     */
    protected $page;

    /**
     * The DOM root element selector
     * @var string
     */
    protected $root;

    /**
     * The DOM root element
     *
     * @var NodeElement
     */
    protected $rootElement;

    /**
     * View parts definition
     *
     * @var array
     */
    protected $parts = array();

    /**
     * Child views
     *
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
     * @{inheritDoc}
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
     * @{inheritDoc}
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * @{inheritDoc}
     */
    public function setRoot($root)
    {
        $this->root = $root;

        return $this;
    }

    /**
     * @{inheritDoc}
     */
    public function getRoot()
    {
        return $this->root;
    }

    /**
     * @{inheritDoc}
     */
    public function add($name, $locator, $type = 'css')
    {
        $this->parts[$name] = array($type, $locator);

        return $this;
    }

    /**
     * @{inheritDoc}
     */
    public function addChild($name, $root)
    {
        $view = new self($this->container, $this->session, $this);
        $view->setRoot($root);

        $this->children[$name] = $view;

        return $view;
    }

    /**
     * @{inheritDoc}
     */
    public function end()
    {
        return $this->parent;
    }

    /**
     * @{inheritDoc}
     */
    public function get($name)
    {
        if (!$this->inited) {
            $this->init();
        }

        return call_user_func_array(array($this->getPage(), 'find'), $this->parts[$name]);
    }

    /**
     * @{inheritDoc}
     */
    public function all($name)
    {
        if (!$this->inited) {
            $this->init();
        }

        return call_user_func_array(array($this->getPage(), 'findAll'), $this->parts[$name]);
    }

    /**
     * @{inheritDoc}
     */
    public function getChild($name)
    {
        if (!$this->inited) {
            $this->init();
        }

        return $this->children[$name];
    }

    /**
     * @{inheritDoc}
     */
    public function getChildByIndex($name, $index)
    {
        $child = clone $this->children[$name];
        $child->index = $index;
        $child->init();

        return $child;
    }

    /**
     * @{inheritDoc}
     */
    public function getShared($name)
    {
        $view = $this->container[$name];
        $view->parent = $this;

        return $view;
    }

    /**
     * @{inheritDoc}
     */
    public function waitFor($main, $timeout = 20000)
    {
        $selector = $this->parts[$main][1];

        $this->session->wait($timeout, sprintf('$("%s:visible").length > 0', $selector));

        return $this;
    }
}
