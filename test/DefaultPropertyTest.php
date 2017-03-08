<?php

namespace Fliglio\Config;

class DefaultPropertySetTest extends \PHPUnit_Framework_TestCase {

	public function testPropertySet() {
		// given
		$cfg = ["foo" => "bar"];
		$p = new DefaultPropertySetProvider($cfg); 

		// when
		$found = $p->build();

		// then
		$this->assertEquals($cfg, $found);
	}

}