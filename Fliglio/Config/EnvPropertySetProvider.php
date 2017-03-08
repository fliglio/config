<?php

namespace Fliglio\Config;

class EnvPropertySetProvider implements PropertySetProvider {
	private $config;

	// only provide an argument for test
	public function __construct($config = null) {
		if (is_null($config)) {
			$config = $_ENV;
		}
		$this->config = $config;		
	}

	public function build() {
		$out = [];

		foreach ($this->config as $key => $value) {
			$key_full = explode('_', $key);
			$key_last = strtolower(array_pop($key_full));
			$pointer  = &$out;

			foreach ($key_full as $key_level) {
				$key_level = strtolower($key_level);

				if (!isset($pointer[$key_level])) {
					$pointer[$key_level] = [];
				}

				$pointer = &$pointer[$key_level];
			}

			$pointer = !is_array($pointer) ? [] : $pointer;
			$pointer[$key_last] = $value;
		}

		return $out;
	}

}