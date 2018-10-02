<?php namespace Teronis\Core;

/**
 * A simple helper class to determine the exceedance of a passed limit.
 */
class SizeLimit {
    private $limit;
    private $size;
    private $hasExceeded;

    public function __construct(float $limit, float $size = 0) {
        $this->limit = $limit;
        $this->size = $size;
        $this->hasExceeded = false;
        $this->calculateHasExceeded();
    }

    // getters

    public function getLimit() {
        return $this->limit;
    }

    public function getSize() {
        return $this->size;
    }

    public function getHasExceeded() {
        return $this->hasExceeded;
    }

    // methods

    public function increaseSizeBy(float $size) {
        $this->size += $size;
        $this->calculateHasExceeded();
    }

    public function decreaseSizeBy(float $size) {
        $this->size -= $size;
        $this->calculateHasExceeded();
    }

    private function calculateHasExceeded() {
        $this->hasExceeded = $this->size > $this->limit;
    }
}