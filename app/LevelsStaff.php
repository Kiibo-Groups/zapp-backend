<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LevelsStaff extends Model
{
    protected $table = 'levels_staff';
    protected $fillable =[
        "name", 
        "nivel", 
        "descript"
    ];
 
    public function addNew($data,$type)
    {
        $add                = $type === 'add' ? new LevelsStaff : LevelsStaff::find($type);
        
        $add->name          = isset($data['name']) ? $data['name'] : '';
        $add->nivel         = isset($data['nivel']) ? $data['nivel'] : 0;
        $add->max_cash      = isset($data['max_cash']) ? $data['max_cash'] : 0;
        $add->descript      = isset($data['descript']) ? $data['descript'] : '';

        $add->save();
    }
   
}
