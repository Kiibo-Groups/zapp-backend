<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Validator;

class BannerItem extends Authenticatable
{
    protected $table = "banner_item";

    public function addNew($data,$id)
    {
        BannerItem::where('banner_id',$id)->delete();

        $item_id = isset($data['item']) ? $data['item'] : [];

        for($i=0;$i<count($item_id);$i++)
        {
            if(isset($item_id[$i]))
            {
                $add                = new BannerItem;
                $add->banner_id     = $id;
                $add->item_id       = $item_id[$i];
                $add->save();
            }
        }
    }
}
