<?php

namespace Fliglio\Config;

use Symfony\Component\Yaml\Yaml;


class FsPropertySetProvider implements PropertySetProvider {

	private $sourceFile;

	public function __construct($sourceFile) {
		$this->sourceFile = $sourceFile;

		if (!is_file($this->sourceFile)) {
			throw new \Exception('Config File not found: '.$this->sourceFile);
		}

		if (!is_readable($this->sourceFile)) {
			throw new \Exception('Cannot read config file: '.$this->sourceFile);
		}
	}

	public function build() {
		$parts = pathinfo($this->sourceFile);

		$config = [];

		if ($parts['extension'] == 'php') {
			$config = require $this->sourceFile;

		} else if ($parts['extension'] == 'yaml' || $parts['extension'] == 'yml') {
			$config = Yaml::parse(file_get_contents($this->sourceFile));
		}

		return $config;
	}

}
