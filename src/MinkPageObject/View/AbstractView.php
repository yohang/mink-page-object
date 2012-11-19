<?php

namespace MinkPageObject\View;

/**
 * The Base class for user-defined views
 *
 * @author Yohan Giarelli <yohan@frequence-web.fr>
 */
abstract class AbstractView extends View
{
    /**
     * @{inheritDoc}
     */
    public function init()
    {
        $this->configure();

        return parent::init();
    }

    /**
     * Abstract Method use de describe and define the View
     */
    abstract public function configure();
}
