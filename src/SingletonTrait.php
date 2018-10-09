<?php namespace Teronis\Core;

trait SingletonTrait {
    private static $instance = null;

    /**
     * Sigleton pattern implementation.
     *
     * @return mixed
     */
    public static function getInstance() {
        if (is_null($instance)) {
            $instance = new self();
        }

        return $instance;
    }
}