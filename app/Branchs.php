<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Validator;
use Auth;

class Branchs extends Authenticatable
{
    protected $table = "branchs";

    /*
    |----------------------------------------------------------------
    |   Validation Rules and Validate data for add & Update Records
    |----------------------------------------------------------------
    */
    
    public function rules($type)
    {
        return [
            'name'      => 'required|unique:branchs',
        ];
    }
    
    public function validate($data,$type)
    {

        $validator = Validator::make($data,$this->rules($type));       
        if($validator->fails())
        {
            return $validator;
        }
    }

    /*
    |--------------------------------
    |Create/Update city
    |--------------------------------
    */

    public function addNew($data,$type)
    { 
        $add                = $type === 'add' ? new Branchs : Branchs::find($type);
        $add->store_id      = Auth::user()->id;
        $add->name          = isset($data['name']) ? $data['name'] : null;
        $add->address       = isset($data['address']) ? $data['address'] : null;
        $add->num_ext       = isset($data['num_ext']) ? $data['num_ext'] : 'S/N';
        $add->num_int       = isset($data['num_int']) ? $data['num_int'] : 'S/N';
        $add->aditional_info = isset($data['aditional_info']) ? $data['aditional_info'] : '';
        $add->lat           = isset($data['lat']) ? $data['lat'] : null;
        $add->lng           = isset($data['lng']) ? $data['lng'] : null;
        $add->status        = isset($data['status']) ? $data['status'] : null; 
        $add->save();

    }   
    /*
    |--------------------------------------
    |Get all data from db
    |--------------------------------------
    */
    public function getAll($type = null)
    {
        return Branchs::where(function($query) use($type) {

            $query->where('branchs.store_id',Auth::user()->id);

            if($type)
            {
                $query->where('status',$type);
            }

        })->orderBy('id','DESC')->get();
    }

    public function getSData($data,$id,$field)
    {
        $data = unserialize($data);

        return isset($data[$id]) ? $data[$id] : null;
    }
}
