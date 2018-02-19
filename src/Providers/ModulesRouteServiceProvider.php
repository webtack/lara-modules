<?php namespace Webtack\Modules\Providers;

use Illuminate\Routing\Router;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;
use Webtack\Modules\Eloquent\Module;
use Webtack\Modules\ModuleStorage;


class ModulesRouteServiceProvider extends ServiceProvider {
	/**
	 * This namespace is applied to the controller routes in your routes file.
	 * In addition, it is set as the URL generator's root namespace.
	 *
	 * @var string
	 */
	protected $namespace = 'Modules';
	
	/**
	 * Define the routes for the application.
	 */
	public function map() {
		$modules = Module::all();
		
		//dd($modules);
		
		foreach ($modules as $module) {
			$routes = $module->routes;
			
			if ($routes) {
				foreach ($routes as $route) {
					if (strrpos($route, "web")) {
						$this->mapWebRoute($route, $module->namespace);
					}
					elseif (strrpos($route, "api")) {
						$this->mapApiRoute($route, $module->namespace);
					}
				}
			}
		}
	}
	
	/**
	 * Define the "web" routes for the application.
	 *
	 * These routes all receive session state, CSRF protection, etc.
	 *
	 * @param string $path
	 *
	 * @param string $moduleName
	 *
	 * @return void
	 */
	protected function mapWebRoute(string $path, string $moduleNameSpace) {
		Route::middleware('web')
		     ->namespace($this->getNamespace($moduleNameSpace))
		     ->group($path);
	}
	
	/**
	 * Define the "api" routes for the application.
	 *
	 * These routes are typically stateless.
	 *
	 * @param string $path
	 *
	 * @param string $moduleName
	 *
	 * @return void
	 */
	protected function mapApiRoute(string $path, string $moduleNameSpace) {
		Route::prefix('api')
		     ->middleware('api')
		     ->namespace($this->getNamespace($moduleNameSpace, true))
		     ->group($path);
	}
	
	/**
	 * @param string $moduleName
	 * @param bool   $api
	 *
	 * @return string
	 */
	protected function getNamespace(string $namespace, bool $api = false) {
		
		if($api)
			return $namespace . "\Api\Controllers";
		else
			return $namespace . "\Controllers";
	}
}
