<?php namespace Webtack\Modules\Traits;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RegexIterator;
use SplFileInfo;

trait FileFinder {
	
	/**
	 * Search config files
	 *
	 * @param string $regex
	 * @param string $path
	 *
	 * @return array
	 */
	protected function findFilesAsSplFileInfo(string $regex, string $path) {
		
		$objects = new RecursiveIteratorIterator(
			new RecursiveDirectoryIterator($path), 
			RecursiveIteratorIterator::SELF_FIRST
		);
		
		$filter = new RegexIterator($objects, $regex);
		$map = [];
		
		foreach ($filter as $entry) {
			$map[] = $entry;
		}
		
		return count($map) > 0 ? $map : [];
	}
	
	/**
	 * @param string      $regex
	 * @param string      $path
	 * @param string|null $propery
	 *
	 * @return array
	 */
	protected function findFilesAsArray(string $regex, string $path) {
		if(!is_dir($path))
			return null;
		
		$files = $this->findFilesAsSplFileInfo($regex, $path);
		$result = [];
		
		foreach ($files as $item) {
			
			if($item instanceof SplFileInfo)				
				$result[] = [
					'realPath' => $item->getRealPath(),
					'path' => $item->getPath(),
					'filename' => $item->getFilename(),
					"type" => $item->getType()
				];
			
		}
		
		return $result;
	}
	
	/**
	 * @param string $regex
	 * @param string $path
	 *
	 * @return array|null
	 */
	protected function findRealPathFiles(string $regex, string $path) {
		if(!is_dir($path))
			return null;
				
		$files = $this->findFilesAsSplFileInfo($regex, $path);
		$result = [];
		
		foreach ($files as $item) {
			
			if($item instanceof SplFileInfo)
				$result[] = $item->getRealPath();			
		}
		
		return !empty($result) ? $result : null;
	}
}