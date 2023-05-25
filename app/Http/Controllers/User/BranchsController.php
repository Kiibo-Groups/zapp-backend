<?php namespace App\Http\Controllers\User;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Banner;
use App\Branchs;
use App\User;
use App\Text;
use App\Language;
use App\Admin;
use DB;
use Validator;
use Redirect;
use IMS;

class BranchsController extends Controller {

	public $folder  = "user/branchs.";
	
    /*
	|---------------------------------------
	|@Showing all records
	|---------------------------------------
	*/
	public function index()
	{					
		$res = new Branchs;
		
		return View($this->folder.'index',[
            'data' => $res->getAll(),
            'link' => env('user').'/branchs/'
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
            'data' => new Branchs,
            'form_url' => env('user').'/branchs',
            'ApiKey'    => Admin::find(1)->ApiKey_google,
        ]);
	}
	
	/*
	|---------------------------------------
	|@Save data in DB
	|---------------------------------------
	*/
	public function store(Request $Request)
	{			
		$data = new Branchs;	
		
		if($data->validate($Request->all(),'add'))
		{
			return redirect::back()->withErrors($data->validate($Request->all(),'add'))->withInput();
			exit;
		}

		$data->addNew($Request->all(),"add");
		
		return redirect(env('user').'/branchs')->with('message','Nueva Sucursal agregada.');
	}
	
	/*
	|---------------------------------------
	|@Edit Page 
	|---------------------------------------
	*/
	public function edit($id)
	{				
		return View($this->folder.'edit',[
            'data' => Branchs::find($id),
            'form_url' => env('user').'/branchs/'.$id,
            'ApiKey'    => Admin::find(1)->ApiKey_google,
        ]);
	}
	
	/*
	|---------------------------------------
	|@update data in DB
	|---------------------------------------
	*/
	public function update(Request $Request,$id)
	{	
		$data = new Branchs;
		$data->addNew($Request->all(),$id);	
		return redirect(env('user').'/branchs')->with('message','Record Updated Successfully.');
	}
	
	/*
	|---------------------------------------------
	|@Delete Data
	|---------------------------------------------
	*/
	public function delete($id)
	{
		Branchs::where('id',$id)->delete();

		return redirect(env('user').'/branchs')->with('message','Record Deleted Successfully.');
	}

	/*
	|---------------------------------------------
	|@Change Status
	|---------------------------------------------
	*/
	public function status($id)
	{
		$res 			= Branchs::find($id);
		$res->status 	= $res->status == 0 ? 1 : 0;
		$res->save();

		return redirect(env('user').'/branchs')->with('message','Status Updated Successfully.');
	}
}