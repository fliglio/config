<?php

namespace Fliglio\Config;

class EnvPropertySetProvider implements PropertySetProvider {

	private $prefix;

	public function __construct($prefix=null) {
		$this->prefix = $prefix;
	}

	public function build() {
		$config = $_SERVER;

		if ($this->prefix) {
			$config = [];
			foreach ($_SERVER as $key => $value) {
				if (substr($key, 0, strlen($this->prefix)) == $this->prefix) {
					$key = substr($key, strlen($this->prefix));
					$config[$key] = $value;
				}
			}
		}

		return $config;
	}

}
