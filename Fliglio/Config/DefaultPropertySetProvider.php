<?php
namespace Fliglio\Config;


class DefaultPropertySetProvider implements PropertySetProvider {
	private $config;

	public function __construct($config) {
		$this->config = $config;
	}
	public function build() {
		return $this->config;
	}
}
