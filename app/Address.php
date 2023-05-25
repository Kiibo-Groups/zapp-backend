<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Validator;
class Address extends Authenticatable
{
    protected $table = 'user_address';

    public function getAll($id)
    {
        return Address::where('user_id',$id)->get();
    }

    public function addNew($data)
    {
        
        $uid            = $data['user_id'];
        $user           = User::find($uid);
        $add            = ($data['add_or_upd'] == 'new') ? new Address : Address::find($data['add_or_upd']);
        $add->user_id   = $uid;
        
        $add->name_who_receives     = isset($data['name_who_receives']) ? $data['name_who_receives'] : AppUser::find($data['user_id'])->name;
        $add->cp                    = isset($data['cp']) ? $data['cp'] : 0;
        $add->state                 = isset($data['state']) ? $data['state'] : 'Colombia';
        $add->municipality          = isset($data['municipality']) ? $data['municipality'] : 'Undefined';
        $add->suburb                = isset($data['suburb']) ? $data['suburb'] : 'Undefined';

        $add->address   = $data['address'];

        $add->num_ext               = isset($data['num_ext']) ? $data['num_ext'] : 0;
        $add->num_int               = isset($data['num_int']) ? $data['num_int'] : 0;
        $add->street_one            = isset($data['street_one']) ? $data['street_one'] : '';
        $add->street_two            = isset($data['street_two']) ? $data['street_two'] : '';

        $add->type      = isset($data['type']) ? $data['type'] : 'Hogar';

        $add->additional_indications   = isset($data['additional_indications']) ? $data['additional_indications'] : '';
        $add->city_id   = isset($data['city_id']) ? $data['city_id'] : 0;
        $add->city_name = isset($data['city_name']) ? $data['city_name'] : 'undefined';
        $add->lat       = isset($data['lat']) ? $data['lat'] : 0;
        $add->lng       = isset($data['lng']) ? $data['lng'] : 0;
        $add->save();

        return ['msg' => 'done','id' => $add->id];
         
    }

    public function Remove($id)
    {
       return Address::where('id',$id)->delete();
    }

    public function MarkPrinAddress($user,$id)
    {
        // Marcamos todas en valor 0
        $add_user = Address::where('user_id',$user)->get();

        foreach ($add_user as $key) {
            $key->add_default = 0;
            $key->save();
        }

        // Marcamos como principal la indicada
        $add_prin = Address::where('user_id',$user)->where('id',$id)->first();
    
        $add_prin->add_default = 1;
        $add_prin->save();

        return true;
    }
}
