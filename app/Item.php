<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Validator;
use Auth;
use Excel;
use DB;
class Item extends Authenticatable
{
    protected $table = "item";
    /*
    |----------------------------------------------------------------
    |   Validation Rules and Validate data for add & Update Records
    |----------------------------------------------------------------
    */
    
    public function rules($type)
    {
        return [

            'name'      => 'required',
            'small_price' => 'numeric|min:0',
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
    |Create/Update user
    |--------------------------------
    */

    public function addNew($data,$type)
    {
        $a                      = isset($data['lid']) ? array_combine($data['lid'], $data['l_name']) : [];
        $b                      = isset($data['lid']) ? array_combine($data['lid'], $data['l_desc']) : [];
        $add                    = $type === 'add' ? new Item : Item::find($type);
        $add->store_id          = Auth::user()->id;
        $add->category_id       = isset($data['cate_id']) ? $data['cate_id'] : null;
        $add->name              = isset($data['name']) ? $data['name'] : null;
        $add->description       = isset($data['description']) ? $data['description'] : null;
        $add->small_price       = isset($data['small_price']) ? $data['small_price'] : 0;
        $add->last_price        = isset($data['last_price']) ? $data['last_price'] : 0;
        $add->medium_price      = isset($data['medium_price']) ? $data['medium_price'] : 0;
        $add->large_price       = isset($data['large_price']) ? $data['large_price'] : 0;
        $add->status            = isset($data['status']) ? $data['status'] : 0;
        $add->sort_no           = isset($data['sort_no']) ? $data['sort_no'] : 0;
        $add->qty               = isset($data['qty']) ? $data['qty'] : 0;
        $add->nonveg            = isset($data['nonveg']) ? $data['nonveg'] : 0;
        $add->s_data            = serialize([$a,$b]);


        if ($type == 'add') { // Es nuevo
            $add->type_img          = 0; // imagen cargada desde dash
            if(isset($data['img']))
            {
                $pic = isset($data['img']) ? $data['img'] : [];
                $picsArray = []; 
                foreach ($pic as $key => $image) 
                {
                    $filename   = time().rand(111,699).'.' .$image->getClientOriginalExtension(); 
                    $image->move("upload/item/", $filename);   
                    $picsArray[] = $filename;
                }

                $add->img = implode(",", $picsArray);
            }
        }else { 
            if($data['prev_img']) {
                $add->img = implode(",", $data['prev_img']);
                $add->save();
            }

            if ($add->type_img == 1) {// ya se habia cargada desde url
                if(isset($data['img']) && $data['img'] != '')
                {
                    $add->type_img = 0; // ya se cargo desde el dash
                    $pic = isset($data['img']) ? $data['img'] : [];
                    $picsArray = []; 
                    foreach ($pic as $key => $image) 
                    {
                        $filename   = time().rand(111,699).'.' .$image->getClientOriginalExtension(); 
                        $image->move("upload/item/", $filename);   
                        $picsArray[] = $filename;
                    }

                    $add->img = implode(",", $picsArray);
                }
            }else {
                $picsArray = [];   

                if(isset($data['img']) && $data['img'] != '')
                {
                    $pic = isset($data['img']) ? $data['img'] : [];
                    foreach ($pic as $key => $image) 
                    {
                        $filename   = time().rand(111,699).'.' .$image->getClientOriginalExtension(); 
                        $image->move("upload/item/", $filename);   
                        $picsArray[] = $filename;
                    }

                    $add->img = $add->img.','.implode(",",$picsArray);
                }

                $add->type_img = 0; // ya se cargo desde el dash
            }
        }

        
        $add->save();
         
        $addon = new ItemAddon;
        $addon->addNew($data,$add->id);
        
    }

    public function updateProd($data,$type)
    {
        $a                      = isset($data['lid']) ? array_combine($data['lid'], $data['l_name']) : [];
        $b                      = isset($data['lid']) ? array_combine($data['lid'], $data['l_desc']) : [];
        $add                    = Item::find($type);
        $add->store_id          = Auth::user()->id;
        $add->category_id       = isset($data['cate_id']) ? $data['cate_id'] : null;
        $add->name              = isset($data['name']) ? $data['name'] : null;
        $add->description       = isset($data['description']) ? $data['description'] : null;
        $add->small_price       = isset($data['small_price']) ? $data['small_price'] : 0;
        $add->last_price        = isset($data['last_price']) ? $data['last_price'] : 0;
        $add->medium_price      = isset($data['medium_price']) ? $data['medium_price'] : 0;
        $add->large_price       = isset($data['large_price']) ? $data['large_price'] : 0;
        $add->status            = isset($data['status']) ? $data['status'] : 0;
        $add->sort_no           = isset($data['sort_no']) ? $data['sort_no'] : 0;
        $add->qty               = isset($data['qty']) ? $data['qty'] : 0;
        $add->nonveg            = isset($data['nonveg']) ? $data['nonveg'] : 0;
        $add->s_data            = serialize([$a,$b]);


        if ($add->type_img == 1) {// ya se habia cargada desde url
            if(isset($data['img']) && $data['img'] != '')
            {
                $add->type_img = 0; // ya se cargo desde el dash
                $pic = isset($data['img']) ? $data['img'] : [];
                $picsArray = []; 
                foreach ($pic as $key => $image) 
                {
                    $filename   = time().rand(111,699).'.' .$image->getClientOriginalExtension(); 
                    $image->move("upload/item/", $filename);   
                    $picsArray[] = $filename;
                }

                $add->img = implode(",", $picsArray);
            }
        }else {
             
            $picsArray = [];  
            if(isset($data['img']) && $data['img'] != '')
            {
                $pic = isset($data['img']) ? $data['img'] : [];
                foreach ($pic as $key => $image) 
                {
                    $filename   = time().rand(111,699).'.' .$image->getClientOriginalExtension(); 
                    $image->move("upload/item/", $filename);   
                    $picsArray[] = $filename;
                }
            }

            
            $image_names = isset($data['prev_img']) ? $data['prev_img'] : [];

            $add->type_img = 0; // ya se cargo desde el dash
            $add->img = implode(",", $image_names).','.implode(",", $picsArray);
        }

        
        $add->save();
        $addon = new ItemAddon;
        $addon->addNew($data,$add->id);
    }

    /*
    |--------------------------------------
    |Get all data from db
    |--------------------------------------
    */
    public function getAll()
    {
        return Item::join('category','item.category_id','=','category.id')
                    ->select('item.category_id','=','0')
                   ->select('item.*','category.name as cate')
                   ->where('item.store_id',Auth::user()->id)
                   ->orderBy('item.id','DESC')->get();
    }

    public function getAllForView()
    {
        return Item::join('category','item.category_id','=','category.id')
                   ->select('item.*','category.name as cate')
                   ->orderBy('item.id','DESC')->get();
    }

    public function getSData($data,$id,$field)
    {
        $data = unserialize($data);

        return $data; //isset($data[$field][$id]) ? $data[$field][$id] : null;
    }

    public function DecodePics($data)
    {
        return json_decode($data);
    }

    public function import($data)
    {
        $array = Excel::toArray(new Item, $data['file']); 

        $i = 0;
        foreach($array[0] as $a)
        {
            $i++;

            if($i > 1)
            {
                if ($a[1] != null) {
                    $add                    = new Item;
                    $add->store_id          = Auth::user()->id;
                    $add->category_id       = $a[2];
                    $add->name              = $a[3];
                    $add->description       = $a[4];
                    $add->status            = $a[5];
                    $add->img               = $a[6];
                    $add->type_img          = 1; // Imagen cargada desde Import
                    $add->small_price       = $a[7];
                    $add->last_price        = $a[8];
                    $add->medium_price      = $a[9];
                    $add->large_price       = $a[10];
                    $add->xlarge_price      = $a[11];
                    $add->sort_no           = $a[12];
                    $add->nonveg            = $a[13];
                    $add->trending          = $a[14];
                    $add->s_data          = $a[15];
                    
                    $add->save();
                }
            }
        }
    }

    public function ExportItemsMeta()
    {
        $item     = [];
         
        $last_price = 0;
		$user = new User;
		$items = Item::orderBy('id','DESC')->get();

		foreach($items as $i)
		{
			// Precio anterior
			$lastPrice = $user->checaValor(intval(str_replace("$","",$i->last_price)));
			if ($i->last_price) {
				$last_price = $lastPrice;
			}

			// Link Deep
			$name_url = strtr($user->getLangItem($i->id,0)['name'],' ','-');

			// Obtenemos la Imagen
			if ($i->type_img == 0) { // Imagen desde el dash
				if(count(explode(",",$i->img)) > 0){
					$img = explode(",",$i->img)[0] ? Asset('upload/item/'.explode(",",$i->img)[0]) : null;
				}else {
					$img = $key ? Asset('upload/item/'.$key) : null;
				}
			}else { // Imagen desde import (URL)
				if(count(explode(",",$i->img)) > 0){
					$img = $i->img ? explode(",",$i->img)[0] : null;
					 
				}else {
					$img = $i->img ? explode(",",$key) : null;
				}
			} 

			// Items
			$item[] = [
				'id'            => $i->id,
				'title'         => ($i->name != '') ? ucfirst(strtolower($i->name)) : 'undefined',
				'description'   => ($i->description != '') ? ucfirst(strtolower($i->description)) : 'undefined',
				'availability'  => 'in stock',
				'condition'     => 'new',
				'price'         => ($i->small_price > 0) ? $i->small_price : 0,
				'link' 			=> 'https://zapplogistica.com/item/'.$name_url,
				'image_link'    => $img,
				'brand'  		=> 'Zapp',
                'google_product_category' => $i->id,
				'last_price'    => $last_price,
				'category' 		=> (isset($user->getLangCate($i->category_id,0)['name'])) ? $user->getLangCate($i->category_id,0)['name'] : '',
			]; 
		}
		
		return $item;
    }

    /*
    |--------------------------------------
    |Get Items query from db
    |--------------------------------------
    */

    function getItemSeach($val, $type, $city_id)
    {

        // Primer Filtro
        $cates  = Item::where(function($query) use($city_id,$val){
                // Que el status del producto este acitov
                $query->where('status',0);
                // Busqueda por LIKE
                if(isset($val))
                {
                    $q   = strtolower($val);
                    $query->whereRaw('Lower(name) like "%' . $q . '%"');
                }
        })->limit(15) // Limite de 15 elementos.
        ->select('category_id')->distinct()->get(); // Lo categorizamos por categoria.

        $currency   = Admin::find(1)->currency; 
        $data     = [];
        $price    = 0;  
        $last_price = 0;


        foreach($cates as $cate)
        {
            // Segundo Filtro
            $items = Item::where(function($query) use($cate,$val){
                // Status activo del producto
                $query->where('status',0);
                // Categoria especifica del primer filtro
                $query->where('category_id',$cate->category_id);

                // Busqueda LIKE
                if(isset($val))
                {
                    $q   = strtolower($val);
                    $query->whereRaw('Lower(name) like "%' . $q . '%"');
                }
            })->limit(15)->orderBy('sort_no','DESC')->get();
            
            $count = [];
            
            foreach($items as $i)
            {
 
                $IPrice = round((intval(str_replace('$','',$i->small_price))),2);
                $lastPrice = round((intval(str_replace("$","",$i->last_price))),2);

                if($i->small_price)
                {
                    $price = $IPrice;
                    $count[] = $IPrice;
                }

                if ($i->last_price) {
                    $last_price = $lastPrice;
                }

                $img = [];
                
                // Obtenemos la Imagen
                if ($i->type_img == 0) { // Imagen desde el dash
                    foreach (explode(",",$i->img) as $key) 
                    {
                        $img[] = $key ? Asset('upload/item/'.$key) : null;
                    }
                }else { // Imagen desde import (URL)
                    // Validamos si existe la imagen en la URL especificada
                    foreach (explode(",",$i->img) as $key) 
                    { 
                        // $img[] = $i->img ? $key : null;
                        if ($i->img) {
                            if ($this->url_exists($key)) {
                                $img[] = $key;
                            }else { $img[] = asset('/assets/img/not_found.jpg'); }
                        }else { $img[] = asset('/assets/img/not_found.jpg'); }
                    }
                } 
                // Verificamos el negocio
                $store = User::find($i->store_id);

                /****** Rating *******/
                $totalRate    = Rate::where('product_id',$i->id)->count();
                $totalRateSum = Rate::where('product_id',$i->id)->sum('star');
                

                if($totalRate > 0)
                {
                    $avg          = $totalRateSum / $totalRate;
                }
                else
                {
                    $avg           = 0 ;
                }
                /****** Rating *******/

                // Rellenamos el Item de productos y categorias
                $item[] = [
                    'id'            => $i->id,
                    'rating'        => $avg,
                    'name'          => $this->getLangItem($i->id,0)['name'],
                    'img'           => $img,
                    'description'   => $this->getLangItem($i->id,0)['desc'],
                    's_price'       => $IPrice,
                    'price'         => $price,
                    'last_price'    => $last_price,
                    'count'         => count($count),
                    'addon'         => $this->addon($i->id),
                    'status'        => $i->status,
                    'store'         => $store,
                ];
            }

            $data[] = [
                'id' => $cate->category_id,
                'sort_no' => $this->getLangCate($cate->category_id,0)['sort_no'],
                'cate_name' => $this->getLangCate($cate->category_id,0)['name'],
                'items' => $item
            ];

            unset($item);

        }

        return $data;
    }

    public function addon($id)
    {
        $i = 0;
        
        $item_addon  = ItemAddon::where('item_id',$id)->select('category_id')->distinct()->get();
        $data = [];
        // $items = [];
        $addon_items = [];
        $item = [];
        $pos = 0;
        
        foreach ($item_addon as $cate) {


            $addons = ItemAddon::where('category_id',$cate->category_id)->where('item_id',$id)->orderBy('category_id','ASC')->get();

            foreach ($addons as $add) {

                $addon = Addon::find($add->addon_id);
                if ($addon) {
                    $item[] = [
                        'id'            => $addon->id,
                        'name'          => $addon->name,
                        'price'         => $addon->price,
                    ];   
                }else {
                    ItemAddon::where('addon_id',$add->addon_id)->delete();
                }
                          
            }
            
            $data[] = [
                'cate_id'       => $this->getLangCate($cate->category_id,0)['id'],
                'cate_sort_no'  => $this->getLangCate($cate->category_id,0)['sort_no'],
                'cate_name'     => $this->getLangCate($cate->category_id,0)['name'],
                'required'      => $this->getLangCate($cate->category_id,0)['required'],
                'single_opcion' => $this->getLangCate($cate->category_id,0)['single_opcion'],
                'max_options'   => $this->getLangCate($cate->category_id,0)['max_options'],
                'items'         => $item
            ];
            
            unset($item);
        }

        
        return $data;
            
    }

    public function getLangCate($id,$lid)
    {
        $lid  = $lid > 0 ? $lid : 0;
        $data = Category::find($id);

        if($lid == 0)
        {
            if($data){
                return [
                'id'            => $data->id,
                'sort_no'       => $data->sort_no,
                'name'          => $data->name,
                'required'      => $data->required,
                'single_opcion' => $data->single_option,
                'max_options'   => $data->max_options
            ]   ;
            }
        }
        else
        {
            $data = unserialize($data->s_data);

            return ['name' => $data[$lid]];
        }
    }

    public function getLangItem($id,$lid)
    {
        $lid  = $lid > 0 ? $lid : 0;
        $data = Item::find($id);
        return ['name' => $data->name,'desc' => $data->description];
    }

    /**
     * Comprobacion de URL de imagenes
     */
    function url_exists( $url = NULL ) {
    
        if( empty( $url ) ){
            return false;
        }
    
        $ch = curl_init( $url );
 
        // Establecer un tiempo de espera
        curl_setopt( $ch, CURLOPT_TIMEOUT, 5 );
        curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT, 5 );

        // Establecer NOBODY en true para hacer una solicitud tipo HEAD
        curl_setopt( $ch, CURLOPT_NOBODY, true );
        // Permitir seguir redireccionamientos
        curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, true );
        // Recibir la respuesta como string, no output
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );

        // Descomentar si tu servidor requiere un user-agent, referrer u otra configuración específica
        // $agent = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.102 Safari/537.36';
        // curl_setopt($ch, CURLOPT_USERAGENT, $agent)

        $data = curl_exec( $ch );

        // Obtener el código de respuesta
        $httpcode = curl_getinfo( $ch, CURLINFO_HTTP_CODE );
        //cerrar conexión
        curl_close( $ch );

        // Aceptar solo respuesta 200 (Ok), 301 (redirección permanente) o 302 (redirección temporal)
        $accepted_response = array( 200, 301, 302 );
        if( in_array( $httpcode, $accepted_response ) ) {
            return true;
        } else {
            return false;
        }
    }
}
