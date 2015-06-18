<?php
namespace Fliglio\Config;


class DefaultPropertySetStash implements PropertySetStash {
	private $config = null;
	public function get() {
		if (is_null($this->config)) {
			throw new PropertySetNotStashedException();
		}
		return $this->config;
	}
	public function set(array $config) {
		$this->config = $config;
	}
	public function reset() {
		$this->config = null;
	}
}
