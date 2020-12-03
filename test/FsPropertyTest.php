<?php

namespace Fliglio\Config;

class FsPropertySetTest extends \PHPUnit_Framework_TestCase {

	private $expected = ['foo' => 'bar', 'baz' => ['foo' => 'bar']];

	public function testPropertySet_Php() {
		// given
		$fsProvider = new FsPropertySetProvider(__DIR__.'/fsInclude.php'); 

		// when
		$output = $fsProvider->build();

		// then
		$this->assertEquals($this->expected, $output);
	}

	public function testPropertySet_Yaml() {
		// given
		$fsProvider = new FsPropertySetProvider(__DIR__.'/fsInclude.yaml'); 

		// when
		$output = $fsProvider->build();

		// then
		$this->assertEquals($this->expected, $output);
	}

}