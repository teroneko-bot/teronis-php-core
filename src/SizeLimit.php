<?php namespace Teronis\Core;

/**
 * A simple helper class to determine the exceedance of a passed limit.
 */
class SizeLimit {
    private $limit;
    private $size;
    private $hasExceeded;

    public function __construct(float $limit, float $size = 0) {
        $this->$limit = $limit;
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

    private function increaseSizeBy(float $size) {
        $this->size += $size;
    }

    private function decreaseSizeBy(float $size) {
        $this->size -= $size;
    }

    private function calculateHasExceeded() {
        $this->hasExceeded = $size > $limit;
    }
}