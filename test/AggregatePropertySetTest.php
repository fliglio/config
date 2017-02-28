<?php

namespace Fliglio\Config;

use SensioLabs\Consul\ServiceFactory;

class AggregatePropertySetTest extends \PHPUnit_Framework_TestCase {

	private $consulkey = 'test_fliglio';
	private $consul = ["consul" => ["foo" => "bar"]];

	public function setup() {
		$_SERVER['test_fliglio_env'] = 'foo';

		$sf = new ServiceFactory();
		$kv = $sf->get('kv');

		$kv->put($this->consulkey, json_encode($this->consul));
	}

	public function tearDown() {
		unset($_SERVER['test_fliglio_env']);
	}

	public function testAggregateProvider_ShallowConfigs() {
		// given
		$p = new AggregatePropertySetProvider([
			new DefaultPropertySetProvider(["foo" => "bar", "baz" => "biz"]),
			new DefaultPropertySetProvider(["b" => "BBBB", "foo" => "updated"]),
			new ConsulPropertySetProvider($this->consulkey),
			new EnvPropertySetProvider('test_')
		]);

		$expected = [
			"b"           => "BBBB", 
			"foo"         => "updated", 
			"baz"         => "biz", 
			"fliglio_env" => "foo",
			"consul"      => ["foo" => "bar"]
		];

		// when
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
