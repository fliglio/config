<?php

namespace Fliglio\Config;

class EnvPropertyTest extends \PHPUnit_Framework_TestCase {

	public function testPrefix() {
		// given
		$_SERVER['test_fliglio_env'] = 'foo';
		$provider = new EnvPropertySetProvider('test_');

		// when
		$config = $provider->build();

		// then
		$this->assertEquals(count($config), 1);
		$this->assertEquals($config['fliglio_env'], 'foo');
	}

	public function testNoPrefix() {
		// given
		$_SERVER['fliglio_env'] = 'foo';
		$provider = new EnvPropertySetProvider();

		// when
		$config = $provider->build();

		// then
		$this->assertEquals(count($config), count($_SERVER));
		$this->assertEquals($config['fliglio_env'], 'foo');
	}

}
