<?php namespace Modules\Test\Api\Controllers;

use App\Http\Controllers\Controller;
use Webtack\Modules\Eloquent\Module;

class TestApiController extends Controller {
	
	public function test() {
		
		$module = Module::get('test');
		
		return response()->json([
				$module->toArray()
		]);
	}
	
}