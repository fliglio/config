<?php

namespace Fliglio\Config;

class EnvPropertyTest extends \PHPUnit_Framework_TestCase {

	public function testNested() {
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

	public function testSimple() {
		// given
		$config = ["FLIGLIO" => 'foo'];
		$provider = new EnvPropertySetProvider($config);

		// when
		$config = $provider->build();

		// then
		$this->assertEquals($config['fliglio'], 'foo');
	}

	public function testChildArrayAndValueOverwrite() {
		// given
		$config = [
			"FOO_BAR" => 'foo',
			"FOO_BAR_BAZ_BOO" => 'hoo'
		];
		$provider = new EnvPropertySetProvider($config);

		// when
		$config = $provider->build();

		// then
		$this->assertEquals('hoo', $config['foo']['bar']['baz']['boo']);
	}

	public function testChildArrayAndValueOverwrite_OppositeOrderArrayWins() {
		// given
		$config = [
			"FOO_BAR_BAZ_BOO" => 'hoo',
			"FOO_BAR" => 'foo'
		];
		$provider = new EnvPropertySetProvider($config);

		// when
		$config = $provider->build();

		// then
		$this->assertEquals('hoo', $config['foo']['bar']['baz']['boo']);
	}

	public function testRootArrayAndValueOverwrite() {
		// given
		$config = [
			"BAX" => 'doo',
			"FOO" => 'foo',
			"FOO_BAR" => 'baz',
			"MOO_STRING_BOOL_FALSE" => 'false',
			"MOO_STRING_BOOL_TRUE" => 'true',
			"TOO_STRING_BOOL_FALSE" => 'FALSE',
			"TOO_STRING_BOOL_TRUE" => 'TRUE',
			"COO_BOOL_FALSE" => false,
			"COO_BOOL_TRUE" => true
		];
		$provider = new EnvPropertySetProvider($config);

		// when
		$config = $provider->build();

		// then
		$this->assertEquals('doo', $config['bax']);
		$this->assertEquals('baz', $config['foo']['bar']);
		$this->assertEquals(['bar' => 'baz'], $config['foo']);
		$this->assertEquals(['false' => false, 'true' => true], $config['coo']['bool']);
		$this->assertEquals(['false' => false, 'true' => true], $config['moo']['string']['bool']);
		$this->assertEquals(['false' => false, 'true' => true], $config['too']['string']['bool']);
	}

	public function testRootArrayAndValueOverwrite_OppositeOrderArrayWins() {
		// given
		$config = [
			"FOO_BAR" => 'baz',
			"FOO" => 'bax'
		];
		$provider = new EnvPropertySetProvider($config);

		// when
		$config = $provider->build();

		// then
		$this->assertEquals('baz', $config['foo']['bar']);
	}

}