<?php
namespace Fliglio\Config;


interface PropertySetStash {

	// @throws ConfigNotStashedException()
	public function get(); // array $config
	public function set(array $config);
	public function reset();
}

