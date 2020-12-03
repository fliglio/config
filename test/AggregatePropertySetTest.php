<?php

namespace Fliglio\Config;

class AggregatePropertySetTest extends \PHPUnit_Framework_TestCase {

	private $config;

	public function setup() {
		$this->config =  ['fliglio_env' => 'foo'];
	}

	public function tearDown() {
		unset($this->config);
	}

	public function testAggregateProvider_ShallowConfigs() {
		// given
		$p = new AggregatePropertySetProvider([
			new DefaultPropertySetProvider(["foo" => "bar", "baz" => "biz"]),
			new DefaultPropertySetProvider(["b" => "BBBB", "foo" => "updated"]),
			new EnvPropertySetProvider($this->config)
		]);

		$expected = [
			"b"           => "BBBB", 
			"foo"         => "updated", 
			"baz"         => "biz", 
			"fliglio"     => ["env" => "foo"]
		];

		// when
		$found = $p->build();

		// then
		$this->assertEquals($expected, $found);
	}

	public function testAggregateProvider_overlayArrayOnString() {
		$cfgA = new DefaultPropertySetProvider([
			"a" => "afoo"
		]);
		$cfgB = new DefaultPropertySetProvider([
			"a"  => ["stream" => "a1"]
		]);

		$expected = [
			"a"  => ["stream" => "a1"]
		];

		// when
		$p = new AggregatePropertySetProvider([$cfgA, $cfgB]);
		$found = $p->build();

		// then
		$this->assertEquals($expected, $found);
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
		$this->assertEquals($expected, $found);
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