<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Validator;
class OrderItem extends Authenticatable
{
   protected $table = 'order_item';

   public function addNew($id,$cartNo)
   {
      $res = Cart::where('cart_no',$cartNo)->get();

      foreach($res as $row)
      {
        
        $add                = new OrderItem;
        $add->order_id      = $id;
        $add->cart_no       = $row->id;
        $add->item_id       = $row->item_id;
        $add->price         = $row->price;
        $add->qty           = $row->qty;
        $add->qty_type      = $row->qty_type;

        $add->save();

        // Descontamos al Item la cantidad
        $item = Item::find($row->item_id);

        // En BD = 20 / Compra = 10
        if ($item->qty > $row->qty) { // 20 > 10 - 20 - 10 = 10 
          $item->qty       = ($item->qty - $row->qty);
        }else {// 5 > 10 - = qty = 0
          $item->qty       = 0;
        }

        $item->save();
        
      }

      
   }

   public function getItem($id)
   {
     $res = OrderItem::join('item','order_item.item_id','=','item.id')
                     ->select('item.name as item','order_item.*')
                     ->where('order_item.order_id',$id)->get();
     $data = [];
     $tot_item = 0;

     foreach($res as $row)
     {
        $it = Item::find($row->item_id);
        $order = Order::find($row->order_id);
        if($row->qty_type == 1)
        {
          $type = "Seleccionado";
          $tot_item = $it->small_price * $row->qty;
        }
        elseif($row->qty_type == 2)
        {
          $type = "Seleccionado";
          $tot_item = $it->medium_price * $row->qty;
        }
        else
        {
          $type = "Seleccionado";
          $tot_item = $it->large_price * $row->qty;
        }

        $u   = new User;
        $lid = isset($_GET['lid']) ? $_GET['lid'] : 0;

        $countRate = Rate::where('product_id',$row->item_id)->where('user_id',$order->user_id)->first();
        
        $data[] = [
          'img'    => $u->getLangItem($row->item_id,$lid)['img'],
          'item'    => $u->getLangItem($row->item_id,$lid)['name'],
          'desc'    => $u->getLangItem($row->item_id,$lid)['desc'],
          'price'   => $row->price,
          'Store_price' => $tot_item,
          'qty'     => $row->qty,
          'type'    => $type,
          'id'      => $row->item_id,
          'addon'   => $this->getAddon($row->cart_no,$row->order_id),
          'cart_no' => $row->cart_no,
          'hasRating' => isset($countRate->id) ? $countRate->star : 0,
        ];
     }

     return $data;
   }

   public function getAddon($id,$oid)
   {
      return OrderAddon::join('addon','order_addon.addon_id','=','addon.id')
                       ->select('addon.name as addon','order_addon.*','addon.price')
                       ->where('order_addon.cart_no',$id)
                       ->where('order_addon.order_id',$oid)
                       ->get();
   }

   public function editOrder($data,$id)
   {
       $store_id = Order::find($id)->store_id;
      OrderItem::where('order_id',$id)->delete();

      $item = isset($data['item_id']) ? $data['item_id'] : [];
      $unit = isset($data['unit']) ? $data['unit'] : [];
      $qty  = isset($data['qty']) ? $data['qty'] : [];

      $comm = User::find($store_id)->c_value;
      $comm_type = User::find($store_id)->c_type;

      for($i=0;$i<count($item);$i++)
      {
          $it = Item::find($item[$i]);

          if($unit[$i] == 1)
          {
              if($comm_type == 0){
                $price = $it->small_price + $comm;
              } else {
                $price = ($it->small_price + $comm) / 100 + $it->small_price;
              }

          }
          elseif($unit[$i] == 2)
          {
            if($comm_type == 0){
                $price = $it->medium_price + $comm;
            } else {
                $price = ($it->medium_price + $comm) / 100 + $it->medium_price;
            }
          }
          else
          {
              if($comm_type == 0) {
                $price = $it->large_price + $comm;
              } else {
                $price = ($it->large_price + $comm) / 100 + $it->large_price;
              }
          }

          $add              = new OrderItem;
          $add->order_id    = $id;
          $add->item_id     = $item[$i];
          $add->qty         = $qty[$i];
          $add->qty_type    = $unit[$i];
          $add->price       = $price;
          $add->save();
      }
   }

   public function RealTotal($id)
   {
      $res = OrderItem::where("order_id",$id)->get();
      $addon = OrderAddon::where('order_id',$id)->get();
      $order = Order::find($id);
      $cart = new Cart;
      $total = 0;

      foreach ($res as $item) {
        $it = Item::find($item->item_id);
        $store = User::find(Order::find($id)->store_id);
       
        $item_id = $item->item_id;

        $small_price  = (is_numeric($item->price)) ? $item->price : 0;
        
        $total += ($small_price) * $item->qty;

      }

   
      if ($addon) {
        foreach($addon as $add)
        {
            $addon_add = Addon::find($add->addon_id);
           if ($addon_add) {
             $total += $addon_add->price;
           }
        }
      }

      return $total;
   }

   public function GetTaxes($id)
   {
      $order  = Order::find($id);
      $store  = User::find($order->store_id);

      $total   = $this->RealTotal($id); 
      $c_type  = $store->c_type;
      $c_value = $store->c_value;

      // IVA sobre la comision
      $t_type_st = $store->t_type_st;
      $t_value_st = $store->t_value_st;
      $comision_st = 0;


      $gananciasxt = 0;
      $retenciones = 0;
      $comisionxs  = $order->t_charges;
      
      $payment_to_receive = 0; 
      $payment_to_admin = 0;

      if ($c_type == 0) { // Valor Fijo
        $retenciones = ($total - $c_value);
      }else { // Valor en  %
        $retenciones = ($total * $c_value) / 100;
      }
    
      if ($t_type_st == 0) { // Valor Fijo
        $comision_st = $t_value_st;
      }else { // Valor en %
        $comision_st = ($retenciones * $t_value_st) / 100;
      }

      $gananciasxt = ($total - $retenciones - $comision_st);
      $payment_to_admin = ($comisionxs + $retenciones + $comision_st);

      return [
        'payment_method' => $order->payment_method,
        'store' => $order->store_id,
        'total' => $total,
        'gananciasxt' => $gananciasxt,
        'comisionxs'   => $comisionxs,
        'reteneciones' => $retenciones,
        'comision_st'  => $comision_st,
        'payment_to_receive' => ($total + $comisionxs),
        'payment_to_admin' => $payment_to_admin
      ];
   }

   public function getTotal($id)
   {
      $count = [];

      foreach($this->detail($id) as $i)
      {
        $count[] = $i->price * $i->qty;
      }

      return array_sum($count);
   }

   public function detail($id)
   {
      return OrderItem::join('item','order_item.item_id','=','item.id')
                     ->select('item.name as item','order_item.*')
                     ->where('order_item.order_id',$id)->get();
   }

}
