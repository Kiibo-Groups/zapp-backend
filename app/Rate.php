<?php

namespace App;

use App\Http\Controllers\NodejsServer;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Validator;
use Auth;
class Rate extends Authenticatable
{
   protected $table = 'rate';

   public function addNew($data)
   {
      $order            = Order::find($data['oid']);
      $chk              = Rate::where('order_id',$data['oid'])->get();
      if ($chk->count() > 0) {
         foreach ($chk as $key) {  
            $add              = Rate::find($key->id);
            $add->user_id     = $data['user_id'];
            
            if ($data['type'] == 'store') {
               $add->store_id    = $order->store_id;
            }else if ($data['type'] == 'product') {
               $add->product_id  = $data['product_id'];
            }else {
               $add->staff_id    = $order->d_boy;
            }

            $add->order_id    = $data['oid'];
            $add->star        = $data['star'];
            if ($data['type'] == 'staff') {
               $add->comment_staff     = isset($data['comment']) ? $data['comment'] : null;
            }else {
               $add->comment     = isset($data['comment']) ? $data['comment'] : null;
            }

            $add->save();
         }
         
      }else {
         $add = new Rate;
         // Agregamos nuevo
         $add->user_id     = $data['user_id'];
         $add->store_id    = ($data['type'] == 'store') ? $order->store_id : 0;
         $add->staff_id    = ($data['type'] == 'staff') ? $order->d_boy : 0;
         $add->order_id    = $data['oid'];
         $add->star        = $data['star'];
         $add->star_staff1 = isset($data['star_staff1']) ? $data['star_staff1'] : 0;
         $add->star_staff2 = isset($data['star_staff2']) ? $data['star_staff2'] : 0;

         if ($data['type'] == 'staff') {
            $add->comment_staff     = isset($data['comment']) ? $data['comment'] : '';
         }elseif ($data['type'] == 'product') {
            $add->product_id  = $data['product_id'];
         }else {
            $add->comment     = isset($data['comment']) ? $data['comment'] : '';
         }
        
         $add->save();
      }
     
      $order->status = 6;
      $order->save();

      // Cambiamos el status en FB 
      $fb_server = new NodejsServer;
      $dat_s = array(
         'external_id' 	=> $order->external_id,
         'status' 		=> $order->status,
         'change_from'   => 'staff_app'
      );
      $fb_server->orderStatus($dat_s); 
      
      if ($data['type'] == 'staff') {
         // Notificamos
         $msg = "El usuario ha calificado tu servicio con ".$data['star'].' estrellas.';
         $title = "Te han calificado por tu servicio.";
         app('App\Http\Controllers\Controller')->sendPushS($title,$msg,$order->store_id);
      }

      return ['data' => true];
   }

   public function getAll($id)
   {
      $rate = Rate::where('staff_id',$id)->get();

      $data = [];
      foreach ($rate as $key) {

         $order = Order::find($key->order_id);
         $store = User::find($key->store_id);

         $data[] = [
            'user'  => $order->name,
            'store' => $store->name,
            'data'  => $key
         ];
      }

      return $data;
   }

   public function GetRate($id)
   {
      return Rate::where(function($query) use($id) {

         $query->where('rate.staff_id',$id);

      })->join('app_user','rate.user_id','=','app_user.id')
      ->select('app_user.name as user','rate.*')
      ->orderBy('rate.id','DESC')->get();
    
   }
}
