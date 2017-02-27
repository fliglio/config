<?php

namespace Fliglio\Config;

use SensioLabs\Consul\ServiceFactory;

class ConsulPropertySetProvider implements PropertySetProvider {

	private $key;

	public function __construct($key="fliglio_config") {
		$this->key = $key;
	}

	public function build() {
		$sf = new ServiceFactory();
		$kv = $sf->get('kv');

		$raw = $kv->get($this->key)->json();

		if (count($raw) != 1) {
			return [];
		}

		return json_decode(base64_decode($raw[0]['Value']), true);
	}

}
