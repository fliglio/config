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

	public function testAggregateProvider_ShallowConfigs() {
		$cfgA = new DefaultPropertySetProvider(["foo" => "bar", "baz" => "biz"]);
		$cfgB = new DefaultPropertySetProvider(["b" => "BBBB", "foo" => "updated"]);;
		
		$expected = ["b" => "BBBB", "foo" => "updated", "baz" => "biz"];

		// when
		$p = new AggregatePropertySetProvider([$cfgA, $cfgB]);
		$found = $p->build();

		// then
		$this->assertEquals($found, $expected);
	}
	public function testAggregateProvider_DeepConfigs() {
		$cfgA = new DefaultPropertySetProvider([
			"a"   => "afoo",
			"a2"  => "afoo",
			"arr" => ["a1", "a2", "a3"],
			"obj" => [
				"a" => "afoo",
				"arr" => ["a1", "a2", "a3"],
				"obj" => [
					"a"  => "afoo",
					"a2" => "afoo",
				]
			],
		]);
		$cfgB = new DefaultPropertySetProvider([
			"a"   => "bfoo",
			"b2"  => "bfoo",
			"arr" => ["b1", "b2"],
			"obj" => [
				"a" => "bfoo",
				"arr" => ["b1", "b2"],
				"obj" => [
					"arr" => ["a1", "a2", "a3"],
					"a"  => "bfoo",
					"b2" => "bfoo",
				]
			],
		]);
		
		$expected = [
			"a"   => "bfoo",
			"a2"  => "afoo",
			"b2"  => "bfoo",
			"arr" => ["b1", "b2"],
			"obj" => [
				"a" => "bfoo",
				"arr" => ["b1", "b2"],
				"obj" => [
					"arr" => ["a1", "a2", "a3"],
					"a"  => "bfoo",
					"a2" => "afoo",
					"b2" => "bfoo",
				]
			],
		];

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
