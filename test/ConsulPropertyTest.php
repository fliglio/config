<?php

namespace Fliglio\Config;

use SensioLabs\Consul\ServiceFactory;

class ConsulPropertyTest extends \PHPUnit_Framework_TestCase {

	private $key = 'test_fliglio';
	private $expected = ["foo" => "bar"];

	public function setup() {
		$sf = new ServiceFactory();
		$kv = $sf->get('kv');

		$kv->put($this->key, json_encode($this->expected));
	}

	public function testPropertySet() {
		// given
		$consulProvider = new ConsulPropertySetProvider($this->key); 

		// when
		$output = $consulProvider->build();

		// then
		$this->assertEquals($output, $this->expected);
	}

}