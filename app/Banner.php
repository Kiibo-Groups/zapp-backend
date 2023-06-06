<?php
namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Validator;

use Auth;
class Banner extends Authenticatable
{
    protected $table = "banner";

     /*
    |--------------------------------------
    |Add/Update Data
    |--------------------------------------
    */
    public function addNew($data,$type)
    {
        $add                    = $type === 'add' ? new Banner : Banner::find($type);
        $add->city_id           = isset($data['city_id']) ? $data['city_id'] : 0;
        $add->status            = isset($data['status']) ? $data['status'] : 0;
        $add->design_type       = isset($data['design_type']) ? $data['design_type'] : 0;
        $add->position          = isset($data['position']) ? $data['position'] : 0;

        if(isset($data['img']))
        {
            $filename   = time().rand(111,699).'.' .$data['img']->getClientOriginalExtension(); 
            $data['img']->move("public/upload/banner/", $filename);   
            $add->img = $filename;   
        }

        $add->save();

        if ($add->design_type == 0) { // Store
            BannerItem::where('banner_id',$add->id)->delete();

            $store = new BannerStore;
            $store->addNew($data,$add->id);
        }else {
            BannerStore::where('banner_id',$add->id)->delete();
            $item  = new BannerItem;
            $item->addNew($data,$add->id);
        }
    }

    /*
    |--------------------------------------
    |Get all data from db
    |--------------------------------------
    */
    public function getAll($city = 0,$type = 'all')
    { 

        if($city > 0){
            return Banner::where(function($query) use($city,$type){

                if($type !== 'all')
                {
                    $query->where('banner.position',$type);
                }
    
                if($city > 0)
                {
                    $query->where('banner.status',0)->whereIn('banner.city_id',[0,$city]);
                }
    
    
            })->leftjoin('city','banner.city_id','=','city.id')
                         ->select('city.name as city','banner.*')
                         ->orderBy('id','DESC')->get();
        }else{ 
           return Banner::where(function($query) use($city,$type){

                if($type !== 'all')
                {
                    $query->where('banner.position',$type);
                }
    
                if($city > 0)
                {
                    $query->where('banner.status',0)->whereIn('banner.city_id',[0,$city]);
                }
    
    
            })->leftjoin('city','banner.city_id','=','city.id')
                         ->select('city.name as city','banner.*')
                         ->orderBy('id','DESC')->get();
        }
    }

    public function getPosition($type)
    {
        if($type == 0)
        {
            $return = "Top";
        }
        elseif($type == 1)
        {
            $return = "Middle";
        }
        else
        {
            $return = "Bottom";
        }

        return $return;
    }

    public function getAppData($id,$type)
    {
        $data = [];
        $us  = new User;
        foreach($this->getAll($id,$type) as $row)
        {

            if ($row->design_type == 0) {
                $link   = BannerStore::where('banner_id',$row->id)->pluck('store_id')->toArray();
                $ban_st = BannerStore::where('banner_id',$row->id)->first();
            }else {
                $link   = BannerItem::where('banner_id',$row->id)->pluck('item_id')->toArray();
                $ban_st = BannerItem::where('banner_id',$row->id)->first();
            }
 
            $data[] = [
                'id'        => $row->id, 
                'img'       => Asset('upload/banner/'.$row->img),
                'type'      => $row->design_type,
                'position'  => $row->position,
                'link'      => count($link) > 0 ? true : false,
                'store_id'  => ($ban_st) ? $ban_st->store_id : 0,
                'item'      => ($row->design_type == 1) ? $us->ItemMenu($ban_st->item_id) : []
            ];
        }

        return $data;
    }


    public function getAppDataWeb()
    {
        $data = [];
        $us  = new User;

        $req = Banner::leftjoin('city','banner.city_id','=','city.id')
                     ->select('city.name as city','banner.*')
                     ->orderBy('id','DESC')->get();


        foreach($req as $row)
        {

            if ($row->design_type == 0) {
                $link   = BannerStore::where('banner_id',$row->id)->pluck('store_id')->toArray();
                $ban_st = BannerStore::where('banner_id',$row->id)->first();
            }else {
                $link   = BannerItem::where('banner_id',$row->id)->pluck('item_id')->toArray();
                $ban_st = BannerItem::where('banner_id',$row->id)->first();
            }
 
            $data[] = [
                'id'        => $row->id, 
                'img'       => Asset('upload/banner/'.$row->img),
                'type'      => $row->design_type,
                'position'  => $row->position,
                'link'      => count($link) > 0 ? true : false,
                'store_id'  => ($ban_st) ? $ban_st->store_id : 0,
                'item'      => ($row->design_type == 1) ? $us->ItemMenu($ban_st->item_id) : []
            ];
        }

        return $data;
    }
}
