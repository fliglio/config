<?php

namespace Fliglio\Config;


class PropertySetTest extends \PHPUnit_Framework_TestCase {


	public function testPropertySet() {
		$cfg = ["foo" => "bar"];
		$p = new DefaultPropertySetProvider($cfg); 
	
		// when
		$found = $p->build();

		// then
		
		$this->assertEquals($cfg, $found);
	}

	public function testAggregateProvider() {
		$cfgA = new DefaultPropertySetProvider(["foo" => "bar", "baz" => "biz"]);
		$cfgB = new DefaultPropertySetProvider(["b" => "BBBB", "foo" => "updated"]);;
		
		$expected = ["b" => "BBBB", "foo" => "updated", "baz" => "biz"];

		// when
		$p = new AggregatePropertySetProvider([$cfgA, $cfgB]);
		$found = $p->build();

		// then
		$this->assertEquals($found, $expected);
	}

	public function testCachedPropertySet() {
		$cfg = ["foo" => "bar"];
		$p = new CachedPropertySetProvider(
			new DefaultPropertySetStash(),
			new DefaultPropertySetProvider($cfg)
		);
		
		// when
		$found = $p->build();

		// then
		
		$this->assertEquals($cfg, $found);
	}

}
