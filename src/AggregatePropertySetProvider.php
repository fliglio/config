<?php

namespace Fliglio\Config;

class AggregatePropertySetProvider implements PropertySetProvider {
	private $sources = [];

	public function __construct(array $sources=[]) {
		foreach ($sources as $source) {
			$this->addPropertySetProvider($source);
		}
	}

	public function addPropertySetProvider(PropertySetProvider $source) {
		$this->sources[] = $source;
		return $this;
	}
	
	public function build() {
		return $this->generatePropertySet();
	}

	private function generatePropertySet() {
		$config = [];
		foreach ($this->sources as $source) {
			$config = $this->overlayProperties($config, $source->build());
		}
		return $config;
	}
	/**
	 * Recursively merge two arrays
	 * - scalar values with the same key:  overlay's value is used
	 * - assoc arrays with the same key:   merge two associative arrays
	 * - ordered arrays with the same key: overlay's array is used
	 */
	private function overlayProperties($base, $overlay) {
		foreach ($overlay as $key => $val) {
			if (is_array($val) && $this->isAssociative($val) && isset($base[$key])) {
				$base[$key] = $this->overlayProperties($base[$key], $val);
			} else {
				$base[$key] = $val;
			}
		}
		return $base;
	}

	// test that keys aren't sequential numeric values starting at 0
	private function isAssociative(array $arr) {
		return array_keys($arr) !== range(0, count($arr) - 1);
	}
}

