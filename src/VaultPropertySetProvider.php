<?php

namespace Fliglio\Config;

use Fliglio\Vault\VaultClient;

class VaultPropertySetProvider implements PropertySetProvider {

	private $client;
	private $secretPath;

	public function __construct(VaultClient $client, $secretPath) {
		$this->client = $client;
		$this->secretPath = $secretPath;
	}

	public function build() {
		$data = $this->client->read($this->secretPath)['data'];

		$out = [];

		foreach ($data as $key => $value) {
			$key_full = explode('_', $key);
			$key_last = strtolower(array_pop($key_full));
			$pointer  = &$out;

			foreach ($key_full as $key_level) {
				$key_level = strtolower($key_level);

				if (!isset($pointer[$key_level])) {
					$pointer[$key_level] = [];
				}

				$pointer = &$pointer[$key_level];
			}

			$pointer = !is_array($pointer) ? [] : $pointer;
			if (!isset($pointer[$key_last])) {
				$pointer[$key_last] = $value;
			}
		}

		return $out;
	}

}
