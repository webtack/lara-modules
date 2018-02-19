<?php namespace Webtack\Modules;

use Event;
use Illuminate\Support\ServiceProvider;
use Webtack\Modules\Eloquent\Module;

/**
 * Class ModulesServiceProvider
 * @package Webtack\Modules
 */
class ModulesServiceProvider extends ServiceProvider {
	
	
	public function boot() {
		$this->app->register("Webtack\Modules\Providers\ModulesRouteServiceProvider");
		
		$this->mergeConfigFrom(__DIR__ . './../config/modules.php', 'module');
		$this->publishes([__DIR__ . '/../config/' => config_path() . '/']);
		$this->publishes([__DIR__ . '/../modules/' => base_path(modules_config('root'))]);
		
		$modules = Module::all();
		
		foreach ($modules as $module) {
			
			if ($module->helpers) {
				include $module->helpers;
			}
			
			if ($module->config) {
				$this->mergeConfigFrom($module->config, $module->name);
			}
			
			//view('test::admin')
			if ($module->views) {
				$this->loadViewsFrom($module->views, $module->name);
			}
			
			if ($module->migrations) {
				$this->loadMigrationsFrom($module->migrations);
			}
			
			//trans('test::messages.welcome')
			if ($module->lang) {
				$this->loadTranslationsFrom($module->lang, $module->name);
			}
			
			$listeners = config($module . ".listeners");
			if ($listeners) {
				$this->registerListeners($listeners);
			}
		}		
	}
	
	public function register() {
		$this->app->singleton('module.storage', function () {
			return new \Webtack\Modules\ModuleStorage;
		});
	}
	
	protected function registerAliases(array $aliases) {
		if (!empty($aliases)) {
			foreach ($aliases as $aliasName => $className) {
				$this->app->alias($aliasName, $className);
			}
		}
	}
	
	protected function registerListeners(array $listenersArray) {
		foreach ($listenersArray as $event => $listeners) {
			foreach ($listeners as $listener) {
				Event::listen($event, $listener);
			}
		}
	}
}