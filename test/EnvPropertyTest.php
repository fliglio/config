<?php

namespace Fliglio\Config;

class EnvPropertyTest extends \PHPUnit_Framework_TestCase {

	public function xtestNested() {
		// given
		$config = [
			"test_fliglio_env" => 'foo1',
			"test_fliglio_loc" => 'foo2',
			"test_system_env_user"  => 'foo3',
			"test_system_env_pass"  => 'foo4',
			"mysql_user"  => 'foo5',
			"mysql_pass"  => 'foo6',
			"host"  => 'foo7'
		];
		$provider = new EnvPropertySetProvider($config);

		// when
		$config = $provider->build();

		// then
		$this->assertEquals($config['test']['fliglio']['env'], 'foo1');
		$this->assertEquals($config['test']['fliglio']['loc'], 'foo2');
		$this->assertEquals($config['test']['system']['env']['user'], 'foo3');
		$this->assertEquals($config['test']['system']['env']['pass'], 'foo4');
		$this->assertEquals($config['mysql']['user'], 'foo5');
		$this->assertEquals($config['mysql']['pass'], 'foo6');
		$this->assertEquals($config['host'], 'foo7');
	}

	public function xtestSimple() {
		// given
		$config = ["FLIGLIO" => 'foo'];
		$provider = new EnvPropertySetProvider($config);

		// when
		$config = $provider->build();

		// then
		$this->assertEquals($config['fliglio'], 'foo');
	}

	public function testArrayAndValue() {
		// given
		$config = [
			"BAX" => 'doo',
			"FOO" => 'foo',
			"FOO_BAR" => 'baz'
		];
		$provider = new EnvPropertySetProvider($config);

		// when
		$config = $provider->build();

		// then
		$this->assertEquals($config['bax'], 'doo');
		$this->assertEquals($config['foo']['bar'], 'baz');
		$this->assertEquals($config['foo'], [ 'bar' => 'baz']);
	}

	public function xtestArrayAndValueOppositeOrder() {
		// given
		$config = [
			"FOO_BAR" => 'baz',
			"FOO" => 'bax'
		];
		$provider = new EnvPropertySetProvider($config);

		// when
		$config = $provider->build();

		// then
		$this->assertEquals($config['foo']['bar'], 'baz');
		$this->assertEquals($config['foo'], ['bar' => 'baz']);
	}

}