<?php namespace Modules\Test\Controllers;


use App\Http\Controllers\Controller;
use Webtack\Modules\Eloquent\Module;

class TestController extends Controller {
	
	public function test() {
		
		$module = Module::get('test');
		
		//dd(app());
		
		return view('test::test', ['module'=>$module]);
	}
	
}