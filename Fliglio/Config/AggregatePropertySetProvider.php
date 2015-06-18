<?php
namespace Fliglio\Config;

class AggregatePropertySetProvider implements PropertySetProvider {
	private $sources = [];

	public function __construct(array $sources=[]) {
		foreach ($sources as $source) {
			$this->addPropertySetProvider($source);
		}
	}

	public function addPropertySetProvider(PropertySetProvider $source) {
		$this->sources[] = $source;
		return $this;
	}
	
	public function build() {
		return $this->generatePropertySet();
	}

	private function generatePropertySet() {
		$config = [];
		foreach ($this->sources as $source) {
			$config = $this->applyPropertySetProviders($config, $source);
		}
		return $config;
	}
	private function applyPropertySetProviders(array $config, $source) {
		return array_merge($config, $source->build());
	 }

}

