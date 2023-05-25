<?php namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Controllers\NodejsServer;
use Illuminate\Http\Request;
use Auth;
use App\Delivery;
use App\User;
use App\City;
use App\Admin;
use App\LevelsStaff;
use DB;
use Validator;
use Redirect;
use IMS;
class LevelsStaffController extends Controller {

	public $folder  = "admin/levels.";
	/*
	|---------------------------------------
	|@Showing all records
	|---------------------------------------
	*/
	public function index()
	{
		$admin = new Admin;

        return View($this->folder.'index',[
            'data' => LevelsStaff::get(),
            'link' => env('admin').'/levels/',
            'array'		=> [],
        ]);
	}

 
	/*
	|---------------------------------------
	|@Add new page
	|---------------------------------------
	*/
	public function show()
	{ 
        return View($this->folder.'add',[
            'data' => new LevelsStaff,
            'form_url' => env('admin').'/levels'
        ]);
	}

	/*
	|---------------------------------------
	|@Save data in DB
	|---------------------------------------
	*/
	public function store(Request $Request)
	{
		$data = new LevelsStaff;
		$data->addNew($Request->all(),"add");

		return redirect(env('admin').'/levels')->with('message','Nuevo Nivel agregado...');
	}

	/*
	|---------------------------------------
	|@Edit Page
	|---------------------------------------
	*/
	public function edit($id)
	{
		return View($this->folder.'edit',[
            'data' => LevelsStaff::find($id),
            'form_url' => env('admin').'/levels/'.$id
        ]);
	}
    
    public function update(Request $Request,$id)
    {
        $data = new LevelsStaff;
		$data->addNew($Request->all(),$id);

		return redirect(env('admin').'/levels')->with('message','Nivel Modificado...');
    }

    public function delete($id)
    {
        $level = LevelsStaff::find($id);
        if ($level) {
            $level->delete();
        }
        return redirect(env('admin').'/levels')->with('message','Nivel Eliminado...');

    }

}
