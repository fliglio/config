<?php

namespace Fliglio\Config;

use Fliglio\Vault\VaultClient;

class VaultPropertyTest extends \PHPUnit_Framework_TestCase {

	public function testNested() {
		// given
		$config = [
			"test_fliglio_vault" => 'foo1',
			"test_fliglio_loc" => 'foo2',
			"test_system_env_user"  => 'foo3',
			"test_system_env_pass"  => 'foo4',
			"mysql_user"  => 'foo5',
			"mysql_pass"  => 'foo6',
			"host"  => 'foo7'
		];

		$provider = new VaultPropertySetProvider(new StubVaultClient($config), "fliglio-test");

		// when
		$config = $provider->build();

		// then
		$this->assertEquals($config['test']['fliglio']['vault'], 'foo1');
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
		$provider = new VaultPropertySetProvider(new StubVaultClient($config), "fliglio-test");

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

		$provider = new VaultPropertySetProvider(new StubVaultClient($config), "fliglio-test");

		// when
		$config = $provider->build();

		// then
		$this->assertEquals('doo', $config['bax']);
		$this->assertEquals('baz', $config['foo']['bar']);
		$this->assertEquals(['bar' => 'baz'], $config['foo']);
	}

	public function testArrayAndValueOppositeOrder() {
		// given
		$config = [
			"FOO_BAR" => 'baz',
			"FOO" => 'bax'
		];

		$provider = new VaultPropertySetProvider(new StubVaultClient($config), "fliglio-test");

		// when
		$config = $provider->build();

		// then
		$this->assertEquals(['bar' => 'baz'], $config['foo']);
	}

}

class StubVaultClient extends VaultClient {

	private $data = [];

	public function __construct($data) {
		$this->data = $data;
	}

	public function read($string) {
		return ['data' => $this->data];
	}
}
