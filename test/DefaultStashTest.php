<?php

namespace Fliglio\Config;


class DefaultStashTest extends \PHPUnit_Framework_TestCase {


	public function testDefaultStash() {
		$stash = new DefaultPropertySetStash();
		$cfg = ["foo" => "bar"];
		
		// when
		$stash->set($cfg);
		$found = $stash->get();

		// then

		$this->assertEquals($cfg, $found);
	}
	
	/**
	 * @expectedException Fliglio\Config\PropertySetNotStashedException
	 */
	public function testNotSetException() {
		$stash = new DefaultPropertySetStash();
		
		// when
		$stash->get();
	}

	/**
	 * @expectedException Fliglio\Config\PropertySetNotStashedException
	 */
	public function testDefaultStashReset() {
		$stash = new DefaultPropertySetStash();
		$cfg = ["foo" => "bar"];
		$stash->set($cfg);
		
		// when
		$stash->reset();
		$stash->get();
	}

}
