<?php namespace Webtack\Modules;

use Webtack\Modules\Exceptions\ModuleBadNameException;
use Webtack\Modules\Exceptions\ModuleNotExistException;
use Webtack\Modules\Traits\FileFinder;
use Webtack\Modules\Traits\HasAttributes;

/**
 * Class ModuleStorage
 * The Singleton Object storage current modules
 * @package Webtack\Modules
 */
class ModuleStorage {
	
	use FileFinder;
	use HasAttributes;
	
	protected $delimiter = ".";
	
	public function __construct() {
		$this->init();
	}
	
	public function getStorage() {
		return $this->attributes->all();
	}
	
	public function getModule($name) {
		$attributes = $this->attributes->get($name);
		
		if(!$attributes)
			throw new ModuleNotExistException("Module name:\"" . $name . "\". Does Not Exist");
		
		return $attributes;
	}	
	
	public function setModuleAttributes(string $module, array $attributes) {
		$moduleAttributes = $this->getModule($module);
		$this->setAttributes($module, array_merge($moduleAttributes, $attributes));
	}
	
	protected function init() {
		$modules = modules_config('modules');
		$this->attributes = collect();
		
		foreach ($modules as $module) {
			$moduleRelativePath = $this->getModulePath($module);
			$modulePath = base_path($moduleRelativePath);
			$moduleName = $this->getModuleName($module);
			
			$this->setAttributes($this->getModuleName($module), [
				"name" => $moduleName,
				"namespace" => $this->getNamespace($moduleName),
				"path" => $modulePath,
				"relativePath" => $moduleRelativePath,
				"routes" => $this->findRealPathFiles("/.php$/", $modulePath."/routes"),
				"views" => $this->isDir($modulePath, "views"),
				"migrations" => $this->isDir($modulePath, "migrations"),
				"lang" => $this->isDir($modulePath, "lang"),
				"helpers" => $this->fileExist($modulePath, $moduleName . ".helpers.php"),
				"config" => $this->fileExist($modulePath, $moduleName . ".config.php"),
			]);
		}
	}
	
	protected function getModulePath(string $module) {
		$root = modules_config('root');
		$separator = DIRECTORY_SEPARATOR;
		$delimiter = $this->delimiter;
		$replaceString = $separator . $root . $separator;
		$modulePath = $root . $separator . str_replace($delimiter, $replaceString, $module);
		
		return $modulePath;
	}
	
	protected function getModuleName(string $module) {	
		
		$explode = explode($this->delimiter, $module);
		$moduleName = $explode[ count($explode) - 1 ];
		
		if(!$this->moduleNameValidation($moduleName))
			throw new ModuleBadNameException("The name <" . $moduleName .  ">" . "Is Very Bad. Please use the under_code style in the directory naming conventions. Example: my_super_module_name");
		else return $moduleName;
	}
	
	protected function getNamespace(string $moduleName) {		
		
		$name = explode("_", $moduleName);
		
		foreach ($name as $key => $el)
			$name[$key] = ucfirst($el);
		
		return modules_config('namespace') . "\\" .  implode($name);
	}
	
	protected function moduleNameValidation(string $moduleName) {
		
		$checkA = preg_match('@[A-Z]@u',$moduleName);
		$checkB = preg_match('@-@u',$moduleName);
		
		if($checkA || $checkB) return false;
		else return true;
	}
	
	protected function isDir(string $modulePath, string $dirName) {
		$realPath = $modulePath . "/" . $dirName;
		return is_dir($realPath) ? $realPath : null;
	}
	
	protected function fileExist(string $modulePath, string $fileName) {
		$realPath = $modulePath . "/" . $fileName;
		return file_exists($realPath) ? $realPath : null;
	}
	

	

	
	
}