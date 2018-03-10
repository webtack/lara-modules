<?php

/**
 * Function Helpers from this package
 */

if (!function_exists('modules_config')) {
	
	/**
	 * @param string $key
	 *
	 * @return \Illuminate\Config\Repository|mixed
	 */
	function modules_config(string $key) {
		return config('modules.' . $key);
	}
}

if (!function_exists('modules_storage')) {
	
	/**
	 * @param string $module
	 *
	 * @return array | void
	 */
	function modules_storage(string $module = '', array $attributes = []) {		
		
		if($module !== '' && empty($attributes)) {
			return resolve('module.storage')->getModule($module);	
		}		
		elseif($module !== '' && !empty($attributes)) {
			resolve('module.storage')->setModuleAttributes($module, $attributes);			
		}
		else {
			return resolve('module.storage')->getStorage();
		}
		
		
	}
}