<?php namespace Webtack\Modules\Traits;

/**
 * Trait HasAttributes
 * Controlling the Module Attribute Checksum
 * @package Webtack\Modules\Traits
 */
trait HasAttributes {
	
	/**
	 * @var \Illuminate\Support\Collection
	 */
	protected $attributes;
	
	public function setAttributes($key, $value) {
		$this->attributes->put($key, $value);
	}
	
	public function getAttributes(string $key) {
		return $this->attributes->get($key);
	}
	
	public function toArray() {
		return $this->attributes->toArray();
	}
	
	public function toJson() {
		return $this->attributes->toJson();
	}
	
	/**
	 * @param $name
	 * @return mixed
	 */
	public function __get($name) {
		return $this->attributes->get($name);
	}
	
	/**
	 * @param $key
	 * @param $value
	 */
	public function __set($key, $value) {
		$this->attributes->put($key, $value);
	}
	
}