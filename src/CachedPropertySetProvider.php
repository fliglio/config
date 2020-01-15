<?php
namespace Fliglio\Config;

class CachedPropertySetProvider implements PropertySetProvider {
	private $stash;
	private $provider;

	public function __construct(PropertySetStash $stash, PropertySetProvider $provider) {
		$this->stash = $stash;
		$this->provider = $provider;
	}

	public function build() {
		try {
			return $this->stash->get();
		} catch(PropertySetNotStashedException $e) {
			$this->stash->set($this->provider->build());
		}
		return $this->stash->get();
	}
}
