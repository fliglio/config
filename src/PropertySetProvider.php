<?php
namespace Fliglio\Config;


interface PropertySetProvider {
	public function build(); // returns config array
}
