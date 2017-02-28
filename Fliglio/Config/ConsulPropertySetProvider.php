<?php

namespace Fliglio\Config;

use SensioLabs\Consul\ServiceFactory;
use SensioLabs\Consul\Exception\ServerException;

class ConsulPropertySetProvider implements PropertySetProvider {

	private $key;

	public function __construct($key="fliglio_config") {
		$this->key = $key;
	}

	public function build() {
		$config = [];

		try {
			$sf = new ServiceFactory();
			$kv = $sf->get('kv');

			$raw = $kv->get($this->key)->json();

			if (count($raw) == 1) {
				$config = json_decode(base64_decode($raw[0]['Value']), true);
			}
		} catch (ServerException $e) {}

		return $config;
	}

}
