<?php namespace Webtack\Modules\Eloquent;

use Webtack\Modules\Traits\HasAttributes;

/**
 * Class Module
 * Class for managing the properties of an Object of the Module type 
 * 
 * @package Webtack\Modules\Eloquent
 */
class Module {
	
	use HasAttributes;
	
	public function __construct(array $attributes = []) {
		$this->attributes = collect($attributes);
	}
	
	/**
	 * @return \Illuminate\Support\Collection
	 */
	public static function all() {
		$storage = modules_storage();
		$result = collect();
		foreach ($storage as $attributes) {
			$result->push(self::getInstance($attributes));
		}
		
		return $result;
	}
	
	/**
	 * @param string $module
	 *
	 * @return \Webtack\Modules\Eloquent\Module
	 */
	public static function get(string $module) {
		$attributes = modules_storage($module);
		
		return self::getInstance($attributes);
	}
	
	/**
	 * @param string $attribute
	 *
	 * @return array|null
	 */
	public static function allAttributesFrom(string $attribute) {
		$modules = self::all();
		$result = [];
		foreach ($modules as $module) {
			if ($module->$attribute) {
				$attr = $module->$attribute;
				
				if (is_array($attr)) {
					foreach ($attr as $item) {
						$result[] = $item;
					}
				}
				else {
					$result[] = $attr;
				}
			}
		}
		
		return empty($result) ? null : $result;
	}
	
	public static function set(string $module, array $attributes) {
		modules_storage($module, $attributes);
	}
	
	/**
	 * @return \Webtack\Modules\Eloquent\Module | mixed
	 */
	public static function current($object, $attribute = null) {		
		
		$reflection = new \ReflectionClass($object);
		$fileName = $reflection->getFileName();$pathToArray = explode(DIRECTORY_SEPARATOR, $fileName);
		$pathToArray = array_reverse($pathToArray);
		$indexParentRoot = array_search(modules_config('root'),$pathToArray);
		$moduleName = $pathToArray[$indexParentRoot-1];
		
		return $attribute ? static::get($moduleName)->getAttributes($attribute) : static::get($moduleName);
	}
	
	/**
	 * @param $attributes
	 *
	 * @return \Webtack\Modules\Eloquent\Module
	 */
	private static function getInstance($attributes) {
		return new Module($attributes);
	}
	
	
	public function __toString() {
		return $this->name;
	}
	
}