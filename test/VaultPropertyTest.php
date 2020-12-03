<?php

namespace Fliglio\Config;

use Fliglio\Vault\VaultClient;

class VaultPropertyTest extends \PHPUnit_Framework_TestCase {

	const SECRET_PATH = "secret/fliglio-test";

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

		$provider = new VaultPropertySetProvider(new StubVaultClient($config), self::SECRET_PATH);

		// when
		$config = $provider->build();

		// then
		$this->assertEquals('foo1', $config['test']['fliglio']['vault']);
		$this->assertEquals('foo2', $config['test']['fliglio']['loc']);
		$this->assertEquals('foo3', $config['test']['system']['env']['user']);
		$this->assertEquals('foo4', $config['test']['system']['env']['pass']);
		$this->assertEquals('foo5', $config['mysql']['user']);
		$this->assertEquals('foo6', $config['mysql']['pass']);
		$this->assertEquals('foo7', $config['host']);
	}

	public function testSimple() {
		// given
		$config = ["FLIGLIO" => 'foo'];
		$provider = new VaultPropertySetProvider(new StubVaultClient($config), self::SECRET_PATH);

		// when
		$config = $provider->build();

		// then
		$this->assertEquals('foo', $config['fliglio']);
	}

	public function testArrayAndValue() {
		// given
		$config = [
			"BAX" => 'doo',
			"FOO" => 'foo',
			"FOO_BAR" => 'baz'
		];

		$provider = new VaultPropertySetProvider(new StubVaultClient($config), self::SECRET_PATH);

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

		$provider = new VaultPropertySetProvider(new StubVaultClient($config), self::SECRET_PATH);

		// when
		$config = $provider->build();

		// then
		$this->assertEquals(['bar' => 'baz'], $config['foo']);
	}

}

class StubVaultClient extends VaultClient {

	private $data = [];

	public function __construct($data) {
		$this->data = [VaultPropertyTest::SECRET_PATH => $data];
	}

	public function read($string) {
		return ['data' => $this->data[$string]];
	}
}
