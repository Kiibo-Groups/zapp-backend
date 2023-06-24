<?php namespace App\Http\Controllers\api;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Controllers\OpenpayController;
use App\Http\Controllers\WompiController;
use App\Http\Controllers\NodejsServer;
use App\Http\Controllers\WhatsAppCloud;

use Illuminate\Http\Request;
use Auth;
use App\City;
use App\OfferStore;
use App\Offer;
use App\User;
use App\Cart;
use App\CartCoupen;
use App\AppUser;
use App\Order;
use App\Order_staff;
use App\OrderAddon;
use App\Item;
use App\OrderItem;
use App\Lang;
use App\Rate;
use App\Slider;
use App\Banner;
use App\Address;
use App\Admin;
use App\Page;
use App\Language;
use App\Text;
use App\Delivery;
use App\CategoryStore;
use App\Opening_times;
use App\CardsUser;
use App\Favorites;
use App\Tables;
use App\Commaned;
use App\Visits;
use App\Deposit;


use App\Exports\MetaExport;

use DB;
use Validator;
use Redirect;
use Excel;
use Stripe;

class ApiController extends Controller {

	public function welcome()
	{
		$res = new Slider;

		return response()->json(['data' => $res->getAppData()]);
	}

	public function city()
	{
		$city = new City;
        $text = new Text;
        $lid =  isset($_GET['lid']) && $_GET['lid'] > 0 ? $_GET['lid'] : 0;

		return response()->json([
			'data' => $city->getAll(0),
			'text' => $text->getAppData($lid)
		]);
	}

	public function GetNearbyCity()
	{
		$city = new City;
        $text = new Text;
        $lid =  isset($_GET['lid']) && $_GET['lid'] > 0 ? $_GET['lid'] : 0;

		return response()->json([
			'data' => $city->GetNearbyCity(0),
			'text' => $text->getAppData($lid)
		]);
	}

	public function updateCity()
	{
		$res = AppUser::find($_GET['id']);
		$res->last_city = $_GET['city_id'];
		$res->save();

		return response()->json(['data' => 'done']);
	}

	public function lang()
	{
		$res = new Language;

		return response()->json(['data' => $res->getWithEng()]);
	}

	public function getDataInit()
	{
		$text    = new Text;
		$l 		 = Language::find($_GET['lid']);
		$items   = new Item;

		$data = [
			'text'		=> $text->getAppData($_GET['lid']),
			'app_type'	=> isset($l->id) ? $l->type : 0,
			'admin'		=> Admin::find(1),
			'items'     => Item::select('id','name')->OrderBy('id','DESC')->get()
		];

		return response()->json(['data' => $data]);
		
	}

	public function homepage($city_id)
	{
		$banner  = new Banner;
		$store   = new User;
		$text    = new Text;
		$offer   = new Offer;
		$cats    = new CategoryStore;
		$cat     = isset($_GET['cat']) ? $_GET['cat'] : 0;
		$l 		 = Language::find($_GET['lid']);

		$data = [
			'admin'		=> Admin::find(1),
			'banner'	=> $banner->getAppData($city_id,0), 
			'store'		=> $store->getAppData($city_id), 
			'Categorys' => $cats->getSelectCat($cat), 
		];

		return response()->json(['data' => $data]);
	}
	
	public function homepage_init($city_id)
	{
		$banner  = new Banner;
		$user	 = new User;
		$cats    = new CategoryStore;
		
		$data = [
			'admin'		=> Admin::find(1),
			'Categorys' => $cats->ViewOrderCats(),
			'banner'	=> $banner->getAppData($city_id,0),
		];

		return response()->json(['data' => $data]);
	}

	public function getTrendings($city_id)
	{
		$user	 = new User;
		
		$data = [
			'trending'	=> $user->InTrending($city_id),
			'products_trend' => $user->menuTrend(0)
		];

		return response()->json(['data' => $data]);
	}

	public function ViewAllCats()
	{
		try {
			$cats    = new CategoryStore;
			$cat     = isset($_GET['cat']) ? $_GET['cat'] : 0;
			$data = [
				'Categorys' => $cats->getSelectSubCat($cat),
			];

			return response()->json(['data' => $data]);
		} catch (\Exception $th) {
			return response()->json(['data' => 'error','error' => $th->getMessage()]);
		}
	}

	public function getStoreOpen($city_id)
	{
		$store   = new User; 
		$data = [
			'store'		=> $store->getStoreOpen($city_id),
			'admin'		=> Admin::find(1),
		];

		return response()->json(['data' => $data]);		
	}

	public function getStore($id)
	{ 

		try {
			$store   = new User;
			return response()->json(['data' => $store->getStore($id)]);
		} catch (\Exception $th) {
			return response()->json(['data' => 'error','error' => $th->getMessage()]);
		}
		
	}

	public function GetInfiniteScroll($city_id) {
		
		$store   = new User;
		
		$data = [
			'store'		=> $store->GetAllStores($city_id)
		];

		return response()->json(['data' => $data]);
	}

	public function getMoreItems($id)
	{
		try {
			$store   = new User;
			return response()->json(['data' => $store->getMoreItems($id)]);
		} catch (\Exception $th) {
			return response()->json(['data' => 'error','error' => $th->getMessage()]);
		}
		
	}

	public function getTypeDelivery($id)
	{
		$user = new User;
		return response()->json([$user->getDeliveryType($id)]);
	}

	public function search($query,$type,$city)
	{
		try {
			$user = new Item;
			return response()->json(['data' => $user->getItemSeach($query,$type,$city)]);
		} catch (\Exception $th) {
			return response()->json(['data' => 'error', 'error' => $th->getMessage()]);
		}
	}

	public function viewSearch($prod,$query)
	{
		try {
			$user = new Item;
			return response()->json(['status' => 200,'data' => $user->getAllSeach($prod,$query)]);
		} catch (\Exception $th) {
			return response()->json(['data' => 'error', 'error' => $th->getMessage()]);
		}
	}

	public function SearchCat($city_id)
	{
		try {
			$user = new User;
			return response()->json([
				'cat'	=> CategoryStore::find($_GET['cat'])->name,
				'data' 	=> $user->SearchCat($city_id)
			]);
		} catch (\Exception $th) {
			return response()->json(['data' => 'error', 'error' => $th->getMessage()]);
		}
	}

	public function SearchFilters($city_id)
	{
		try {
			$user = new User; 
			return response()->json([
				'data' 	=> $user->SearchFilters($city_id)
			]);
		} catch (\Exception $th) {
			return response()->json([
				'data' 	=> 'error',
				'error' => $th->getMessage()
			]);
		}
	}

	public function addToCart(Request $Request)
	{
		$res = new Cart;

		return response()->json(['data' => $res->addNew($Request->all())]);
	}

	public function updateCart($id,$type)
	{
		$res = new Cart;
		return response()->json(['data' => $res->updateCart($id,$type)]);
	}

	public function cartCount($cartNo)
	{
		try {
			if(isset($_GET['user_id']) && $_GET['user_id'] > 0)
			{
				$order = Order::where('user_id',$_GET['user_id'])->whereIn('status',[0,1,1.5,3,4,5])->count();
			}
			else
			{
				$order = 0;
			}

			$cart = new Cart;
			$req  = new Order;

			return response()->json([
				'data'  => Cart::where('cart_no',$cartNo)->count(),
				'order' => $order,
				'data_order' => ($order > 0) ? Order::where('user_id',$_GET['user_id'])->whereIn('status',[0,1,1.5,3,4,5])->first()->external_id : '',
				'list_orders' => ($order > 0) ? $req->getListOrder($_GET['user_id']) : [],
				'cart'	=> $cart->getItemQty($cartNo)
			]);
		} catch (\Exception $th) {
			return response()->json(['data' => 'error','error' => $th->getMessage()]);
		}
	}

	public function getCart($cartNo)
	{ 	
		try {
			$res = new Cart;
			return response()->json(['data' => $res->getCart($cartNo)]);	
		} catch (\Exception $th) {
			return response()->json(['data' => 'error','error' => $th->getMessage()]);
		}
	}

	public function deleteAll($cartNo)
	{
		$res = new Cart;

		return response()->json(['data' => $res->deleteAll($cartNo)]);
	}

	public function getOffer($cartNo)
	{
		$res = new Offer;

		return response()->json(['data' => $res->getOffer($cartNo)]);
	}

	public function applyCoupen($id,$cartNo)
	{
		$res = new CartCoupen;

		return response()->json($res->addNew($id,$cartNo));
	}

	public function signup(Request $Request)
	{
		try {
			$res = new AppUser;
			return response()->json($res->addNew($Request->all()));
		} catch (\Exception $th) {
			return response()->json(['msg' => 'error','error' => $th->getMessage()]);
		}
	}

	public function sendOTP(Request $Request)
	{
		$phone = $Request->phone;
		$hash  = $Request->hash;

		return response()->json(['otp' => app('App\Http\Controllers\Controller')->sendSms($phone,$hash)]);
	}

	public function SignPhone(Request $Request)
	{
		$res = new AppUser;

		return response()->json($res->SignPhone($Request->all()));
	}

	public function chkUser(Request $Request)
	{
		try {
			$res = new AppUser;
			return response()->json($res->chkUser($Request->all()));
		} catch (\Exception $th) {
			return response()->json(['msg' => 'error','error' => $th->getMessage()]);
		}
	}

	public function login(Request $Request)
	{
		$res = new AppUser; 
		return response()->json($res->login($Request->all()));
	}

	public function Newlogin(Request $Request)
	{
		try {
			$res = new AppUser;
			return response()->json($res->Newlogin($Request->all()));
		} catch (\Exception $th) {
			return response()->json(['msg' => 'error','error' => $th->getMessage()]);
		}
	}

	public function forgot(Request $Request)
	{
		$res = new AppUser;
		return response()->json($res->forgot($Request->all()));
	}

	public function verify(Request $Request)
	{
		$res = new AppUser;

		return response()->json($res->verify($Request->all()));
	}

	public function updatePassword(Request $Request)
	{
		$res = new AppUser;

		return response()->json($res->updatePassword($Request->all()));
	}

	public function loginFb(Request $Request)
	{
		try {
			$res = new AppUser;
			return response()->json($res->loginFb($Request->all()));
		} catch (\Exception $th) {
			return response()->json(['msg' => 'error','error' => $th->getMessage()]);
		}
	}

	public function loginGl(Request $Request)
	{
		try {
			$res = new AppUser;
			return response()->json($res->loginGl($Request->all()));
		} catch (\Exception $th) {
			return response()->json(['msg' => 'error','error' => $th->getMessage()]);
		}
	}

	public function getAddress($id)
	{
		$address = new Address;
		$cart 	 = new Cart;

		$data 	 = [
		'address'	 => $address->getAll($id),
		'Comercio'   => User::find($_GET['store']),
		'total'   	 => $cart->getCart($_GET['cart_no'])['total'],
		'c_charges'  => $cart->getCart($_GET['cart_no'])['c_charges']
		];

		return response()->json(['data' => $data]);
	}

	public function getAllAdress($id)
	{
		try {
			$address = new Address;
			return response()->json(['data' => $address->getAll($id)]);
		} catch (\Exception $th) {
			return response()->json(['data' => 'error', 'error' => $th->getMessage()]);
		}
	}

	public function addAddress(Request $Request)
	{
		try {
			$res = new Address;
			return response()->json($res->addNew($Request->all()));
		} catch (\Exception $th) {
			return response()->json(['msg' => 'error','error' => $th->getMessage()]);
		}
	}

	public function removeAddress($id)
	{
		$res = new Address;
		return response()->json($res->Remove($id));
	}

	public function MarkPrinAddress($user,$id)
	{
		try {
			$address = new Address;	
			return response()->json(['data' => $address->MarkPrinAddress($user,$id)]);
		} catch (\Exception $th) {
			return response()->json(['data' => 'error', 'error' => $th->getMessage()]);
		}
	}
	
	public function searchLocation(Request $Request)
	{
		$city = new City;
		return response()->json([
			'citys' => $city->getAll()
		]); 
	}

	public function order(Request $Request)
	{
		try {
			$res = new Order;
			return response()->json($res->addNew($Request->all()));
		} catch (\Exception $th) {
			return response()->json(['data' => 'error', 'error' => $th->getMessage()]);
		}
	}

	public function userinfo($id)
	{
		try {
			$user = new AppUser;
			$deposit = new Deposit;
			return response()->json([
				'data' => AppUser::find($id),
				'cashback' => $user->getAllUser($id),
				'deposits' => $deposit->getDeposits($id)
			]);
		} catch (\Exception $th) {
			return response()->json(['data' => 'error', 'error' => $th->getMessage()]);
		}
	}

	public function signupOP(Request $Request)
	{
		try {
			$res = new AppUser;
			return response()->json(['data' => $res->signupOP($Request->all())]);
		} catch (\Exception $th) {
			return response()->json(['data' => "error",'error' => $th->getMessage()]);
		}
	}

	public function updateInfo($id,Request $Request)
	{
		$res = new AppUser;

		return response()->json($res->updateInfo($Request->all(),$id));
	}

	public function cancelOrder($id,$uid)
	{
		try {
			$res = new Order;
			return response()->json($res->cancelOrder($id,$uid));
		} catch (\Exception $th) {
			return response()->json(['data' => 'error', 'error' => $th->getMessage()]);
		}
	}

	public function rate(Request $Request)
	{
		try {
			$rate = new Rate;
			return response()->json($rate->addNew($Request->all()));
		} catch (\Exception $th) {
			return response()->json(['data' => 'error', 'error' => $th->getMessage()]);
		}

	}

	public function pages()
	{
		$res = new Page;

		return response()->json(['data' => $res->getAppData()]);
	}

	public function myOrder($id)
	{
		$res = new Order;
		$req = new Commaned;

		return response()->json([
			'data' 		=> $res->history($id),
			'events' 	=> $req->history($id)
		]);
	}

	public function getChat($id)
	{
		// $message = new WhatsAppCloud;
		// return response()->json(['data' => $message->SendMessage()]);
		$fb_server = new NodejsServer;
		// Enviamos al servidor
		$dat_s = array(
			'order_id'		=> 134,
			'type_staff'    => 1,
		);

		$data_deli = $fb_server->setStaffDelivery($dat_s); 

		return response()->json([
			'data' => $data_deli
		]);
	}

	public function getStatus($id)
	{
		try {
			$order = Order::find($id);
			$dboy  = Delivery::find($order->d_boy);
			$store = User::find($order->store_id);

			return response()->json(['data' => $order,'dboy' => $dboy, 'store' => $store]);
		} catch (\Throwable $th) {
			return response()->json(['data' => [],'dboy' => [], 'store' => []]);
		}
	}

	public function getPolylines()
	{
		$url = "https://maps.googleapis.com/maps/api/directions/json?origin=".$_GET['latOr'].",".$_GET['lngOr']."&destination=".$_GET['latDest'].",".$_GET['lngDest']."&mode=driving&key=".Admin::find(1)->ApiKey_google;
		$max      = 0;

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec ($ch);
        $info = curl_getinfo($ch);
        $http_result = $info ['http_code'];
        curl_close ($ch);


		$request = json_decode($output, true);

		return response()->json($request);
	}

	public function sendChat(Request $Request)
	{
		$chat = new Chat;
		return response()->json($chat->addNew($Request->all()));
	}

	public function deleteOrders (Request $Request)
	{
		$items  = $Request->all()['SendChk'];

		for ($i=0; $i < count($items); $i++) { 
			Order::find($items[$i])->delete();
			Order_staff::where('order_id',$items[$i])->delete();
			OrderAddon::where('order_id',$items[$i])->delete();
			OrderItem::where('order_id',$items[$i])->delete();
		}	

		return response()->json(['data' => 'done']);
	}

	
	/**
	 * 
	 *  Favorites Funcions
	 * 
	 */

	 public function SetFavorite(Request $Request)
	 {
		try {
			$req = new Favorites;
			
			return response()->json(['data' => $req->addNew($Request->all())]);
		} catch (\Throwable $th) {
			return response()->json(['data' => "error"]);
		}
	 }

	 public function GetFavorites($id)
	 {
		try {
			$req = new Favorites;
			return response()->json(['data' => $req->GetFavorites($id)]);	
		} catch (\Exception $th) {
			return response()->json(['data' => "error",'error' => $th->getMessage()]);
		}
	 }

	 public function TrashFavorite($id, $user)
	 {
		try {
			$req = new Favorites;
			return response()->json(['data' => $req->TrashFavorite($id, $user)]);	
		} catch (\Throwable $th) {
			return response()->json(['data' => "error",'error' => $th]);
		}
	 }


	/**
	  * 
	  * Solcitud de repartidores cercanos
	  *
	 */

	public function getNearbyStaffs($order,$type_staff)
	{
		// Obtenemos repartidores Mas cercanos
		$delivery = new Delivery;
		return response()->json(['data' => $delivery->getNearby($order, $type_staff)]);
	}

	public function setStaffOrder($order, $dboy)
	{
		// Chequeo de pedido y registro de repartidores
		$delivery = new Delivery;
		return response()->json(['data' => $delivery->setStaffOrder($order,$dboy)]);	
	}

	public function delStaffOrder($order)
	{
		// Chequeo de pedido y registro de repartidores
		$delivery = new Delivery;
		return response()->json(['data' => $delivery->delStaffOrder($order)]);	
	}

	public function delStaffEvent($event_id)
	{
		$req = new Commaned;
		return response()->json(['data' => $req->delStaffEvent($event_id)]);
	}

	public function updateStaffDelivery($staff, $external_id)
	{
		$staff = Delivery::find($staff);

		$staff->external_id = $external_id;
		$staff->save();

		return response()->json(['data' => 'done']);
	}

	/**
	  * 
	  * Seccion de mandaditos
	  *
	*/

	public function OrderComm(Request $Request)
	{
		try {
			$res = new Commaned;
			return response()->json($res->addNew($Request->all()));
		} catch (\Exception $th) {
			return response()->json(['data' => 'error','error' => $th->getMessage()]);
		}
	}

	public function ViewCostShipCommanded(Request $Request)
	{
		try {
			
			$req = new Commaned; 
			return response()->json(['data' => $req->Costs_shipKM($Request->all())]);
		} catch (\Exception $th) {
			return response()->json(['data' => 'fail', 'error' => $th->getMessage()]);
		}
	}

	public function chkEvents_comm($id)
	{
		try {
			$req = new Commaned;
			return response()->json(['data' => $req->chkEvents_comm($id)]);
		} catch (\Exception $th) {
			return response()->json(['data' => "error",'error' => $th->getMessage()]);
		}
	}

	public function chkEvents_staffs(Request $Request)
	{
		// Reseteamos
		$event = Commaned::find($Request->get("id_order"));
		$event->status = 0;
		$event->save();

		$req = new NodejsServer;
		
		return response()->json(['data' => $req->NewOrderComm($Request->all()),'req' => $Request->all()]);
	}

	public function getNearbyEvents($id)
	{
		try {
			$req = new Commaned;
			return response()->json(['data' => $req->getNearby($id)]);
		} catch (\Exception $th) {
			return response()->json(['data' => $id, 'error' => $th->getMessage()]);
		}
	}

	public function setStaffEvent($event_id,$dboy)
	{
		try {
			$req = new Commaned;
			return response()->json(['data' => $req->setStaffEvent($event_id,$dboy)]);	
		} catch (\Exception $th) {
			return response()->json(['data' => $id, 'error' => $th->getMessage()]);
		}
	}

	

	public function cancelComm_event($event_id)
	{
		$req = new Commaned;
		return response()->json(['data' => $req->cancelComm_event($event_id)]);
	}

	public function rateComm_event(Request $Request)
	{
		try {
			$req = new Commaned;
			return response()->json(['data' => $req->rateComm_event($Request->all())]);
		} catch (\Exception $th) {
			return response()->json(['data' => 'error','error' => $th->getMessage()]);
		}
	}

	public function SetNewVisitStore($store_id,$user_id)
	{
		try {
			$visit = new Visits;
			return response()->json(['data' => $visit->addNew($store_id,$user_id)]);
		} catch (\Exception $th) {
			return response()->json(['data' => 'error','error' => $th->getMessage()]);
		}
	}


	/**
	 * 
	 * Categorias
	 * 
	 */
	public function getCategory($id)
	{
		try {
			$req = new CategoryStore; 
			return response()->json(['data' => $req->getSelectCat($id)]);
		} catch (\Exception $th) {
			return response()->json(['data' => 'error','error' => $th->getMessage()]);
		}
	}

	public function getSelectSubCat($id)
	{
		try {
			$req = new CategoryStore; 
			return response()->json(['data' => $req->getSelectSubCat($id)]);
		} catch (\Exception $th) {
			return response()->json(['data' => 'error','error' => $th->getMessage()]);
		}
	}

	/**
	 * Metodos Stripe
	 */
	public function stripe()
	{

		try {
			Stripe\Stripe::setApiKey(Admin::find(1)->stripe_api_id);

			$res = Stripe\Charge::create ([
					"amount" => $_GET['amount'] * 100,
					"currency" => "MXN",
					"source" => $_GET['token'],
					"description" => "Pago de compra en Zapp Logistica"
			]);

			if($res['status'] === "succeeded")
			{
				return response()->json(['data' => "done",'id' => $res['source']['id']]);
			}
			else
			{
				return response()->json(['data' => "error"]);
			}
		} catch (\Throwable $th) {
			return response()->json(['data' => "error"]);
		}
	}

	/**
	 * Metodos OpenPay
	 */
	public function getClient(Request $Request)
	{
		try {
			$openPay = new OpenpayController;
			return response()->json(['data' => $openPay->getClient($Request->all())]);
		} catch (\Throwable $th) {
			return response()->json(['data' => "error"]);
		}
	}

	public function SetCardClient(Request $Request)
	{
		try {
			$openpay = new OpenpayController;
			$req     = $openpay->SetCardClient($Request->all());
			if ($req['status'] == true) {
				$user = AppUser::find($Request->get('user_id'));
				$card = new CardsUser;
				$data 	 = [
					'user_id'	 	=> $user->id,
					'token_card'   	=> $req['data']['id']
				];

				$card->addNew($data,'add');
			}

			return response()->json(['data' => $req]);
		} catch (\Throwable $th) {
			return response()->json(['data' => "error",'error' => $th]);
		}
	}

	public function GetCards(Request $Request)
	{
		try {
			$openpay = new OpenpayController; 
			return response()->json(['data' => $openpay->getCardsClient($Request->all())]);
		} catch (\Throwable $th) {
			return response()->json(['data' => "error"]);
		}
	}

	public function DeleteCard(Request $Request)
	{
		try {
			$openpay = new OpenpayController;
			
			return response()->json(['data' => $openpay->DeleteCard($Request->all())]);
		} catch (\Throwable $th) {
			return response()->json(['data' => "error"]);
		}
	}

	public function getCard(Request $Request)
	{
		try {
			$openpay = new OpenpayController;
			
			return response()->json(['data' => $openpay->getCard($Request->all())]);
		} catch (\Throwable $th) {
			return response()->json(['data' => "error"]);
		}
	}

	public function chargeClient(Request $Request)
	{
		try {
			$openpay = new OpenpayController;
			
			return response()->json(['data' => $openpay->chargeClient($Request->all())]);
		} catch (\Exception $th) {
			return response()->json(['data' => "error",'msg' => $th->getMessage()]);
		}
	}

	public function addBalance(Request $Request)
	{
		try {
			$data = $Request->all();
			Deposit::create($data);	

			$user = AppUser::find($data['user_id']);

			$saldo = $user->saldo;
			$user->saldo = $saldo + $data['amount'];
			$user->save();

			return response()->json(['data' => 'done']);
		} catch (\Exception $th) {
			return response()->json(['data' => "error",'error' => $th->getMessage()]);
		}
	}
 
	public function setTableCustomer($id)
	{
		try {
			$res 			= Tables::where("mesa",$id)->first();
			if ($res) { 
				if ($res->status == 1) { // La mesa esta tomada
					return response()->json(['data' => 'table_inuse']);
				}else {
					$res->status = 1;
					$res->save();
					return response()->json(['data' => 'done']);
				}
			}else {
				return response()->json(['data' => 'not_found_table']);
			}
		} catch (\Exception $th) {
			return response()->json(['data' => "error",'msg' => $th->getMessage()]);
		}
	}


	/**
	 * 
	 * Metodo de pago Wompi
	 * 
	 */
	public function GenerateToken()
	{
		try {
			$wompi = new WompiController;
			return response()->json(['data' => $wompi->GenerateAcceptanceToken()]);
		} catch (\Exception $th) {
			return response()->json(['data' => 'error','error' => $th->getMessage()]);
		}
	}
	
	public function CreateTokenWompi(Request $Request){
		
		try {
			$wompi = new WompiController;
			return response()->json(['data' => $wompi->GenerateTokenCard($Request->all())]);
		} catch (\Exception $th) {
			return response()->json(['data' => 'error','error' => $th->getMessage()]);
		}
	}

	public function CreateTransactions(Request $Request){
		
		try {
			$wompi = new WompiController;
			return response()->json(['data' => $wompi->CreateTransactions($Request->all())]);
		} catch (\Exception $th) {
			return response()->json(['data' => 'error','error' => $th->getMessage()]);
		}
	}

	/**
     * 
     * DeepLinks
     * Get Store/Item ID
     * 
     */

	public function getStoreID($name)
	{
		try {
			// Ejemplo https://zapplogistica.com/store/f322fc3c473ca08 
			// https://zapplogistica.com/store/997607610e7f712
			// Name -> f322fc3c473ca08 
			
			// $name_rpl = str_replace('-',' ',$name);
			
			$req = User::where(function($query) use($name){
				$query->where('status',0); 
			})->get();

			$store     = 0;
			$flagChk  = false;

			foreach ($req as $key) {
				$name_db = substr(md5($key->name),0,15);

				if ($name_db == $name) {
					$flagChk = true;
					$store = $key;
					break;					
				}
			}

			if ($flagChk) {
				return response()->json(['data' => $store->id]);
			}else {
				return response()->json(['data' => 'not_found']);
			}
		} catch (\Exception $th) {
			return response()->json(['data' => 'error','error' => $th->getMessage()]);
		}
	}

	public function getItemID($name)
	{
		try {
			// Ejemplo https://zappstore.com.co/store/a1b5a7711562116
			// https://zappstore.com.co/item/b32eba5187373aa
			// https://zappstore.com.co/.well-known/assetlinks.json
			// Name -> f69658d02f3b3fa 

			$name_rpl = str_replace('-',' ',$name);
			
			$req = Item::where(function($query) use($name){
				$query->where('status',0);
			})->get();

			$data     = [];
			$item     = 0;
			$flagChk  = false;
			$us       = new User;

			foreach ($req as $key) {
				$name_db = substr(md5($key->name),0,15);

				if ($name_db == $name) {
					$flagChk = true;
					$item = $key;
					break;					
				}
			}

			if ($flagChk) {
				
				$cates    = Item::where('store_id',$item->store_id)->get();
				$price    = 0;
				$last_price = 0;
				
				foreach($cates as $i)
				{
					$IPrice = round((intval(str_replace('$','',$i->small_price))),2);
					$lastPrice = round(intval(str_replace("$","",$i->last_price)),2);

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
						foreach (explode(",",$i->img) as $key) 
						{ 
							$img[] = $i->img ? explode(",",$key) : null;
						}
					}


					if ($item->id == $i->id) {
						// Items
						$data = [
							'id'            => $i->id,
							'name'          => $us->getLangItem($i->id,0)['name'],
							'img'           => $img,
							'description'   => $us->getLangItem($i->id,0)['desc'],
							's_price'       => $IPrice,
							'price'         => $price,
							'last_price'    => $last_price,
							'count'         => count($count),
							'nonveg'        => $i->nonveg,
							'addon'         => $us->addon($i->id),
							'status'        => $i->status
						];
					}
					

				}

				return response()->json(['data' => $data]);
			
			}else {
				return response()->json(['data' => 'not_found','name' => $name]);
			}
		} catch (\Exception $th) {
			return response()->json(['data' => 'error','error' => $th->getMessage()]);
		}
	}


	/**
     * 
     * API para Facebook
     * 
     */

	public function getItemsMeta($type)
	{
		return Excel::download(new MetaExport, 'xml_fb.csv');
	}

	public function showItemsMeta()
	{
		$url = "https://app.zappstore.com.co/api/getItemsMeta";
        $xml = simplexml_load_file($url);

		$folder = "meta.";

        /* Aquí lo mejor es manipular la información de tu XML de acuerdo a lo que se mostrará en la vista */
		return View($folder.'xml_fb',[
			['xmlContent' => $xml]
		]);
	}



	/**
     * Funciones para el Sitio web
     */
    public function homepage_web()
	{
		$banner  = new Banner; 
		$cats    = new CategoryStore;
	
		$data = [
			'Categorys' => $cats->ViewOrderCats(),
			'banner'	=> $banner->getAppDataWeb()
		];

		return response()->json(['data' => $data]);
	}

	public function searchWeb($query)
	{
		try {
			$user = new Item;
			return response()->json(['data' => $user->getItemSeach($query,0,0)]);
		} catch (\Exception $th) {
			return response()->json(['data' => 'error', 'error' => $th->getMessage()]);
		}
	}

	public function getItemWeb($id)
	{
		try {
			
			 
			$data     = [];
			$item     = [];
			$flagChk  = false;
			$us       = new User;
			$it       = new Item; 
  
			$i    = Item::find($id);
			$price    = 0;
			$last_price = 0;
			
			$IPrice = round((intval(str_replace('$','',$i->small_price))),2);
			$lastPrice = round(intval(str_replace("$","",$i->last_price)),2);

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
				foreach (explode(",",$i->img) as $key) 
				{ 
					$img[] = $i->img ? explode(",",$key) : null;
				}
			}

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


			/****** Reseñas *******/
			$revs = Rate::where('product_id',$i->id)->get();
			$reviews = [];
			foreach ($revs as $rv) {

				$user_rv = AppUser::where('id',$rv->user_id)->get(['name','email']);

				$reviews[] = [
					'user' => ($user_rv) ? $user_rv[0] : 'Indefinido',
					'comment' => $rv->comment,
					'stars'   => $rv->star,
					'created_at'	=> $rv->created_at
				];
			}

			/****** Reseñas *******/
			

			$store = User::find($i->store_id);

			// Items
			$item = [
				'id'            => $i->id,
				'rating'        => $avg,
				'reviews'		=> $reviews,
				'name'          => $it->getLangItem($i->id,0)['name'],
				'img'           => $img,
				'description'   => $it->getLangItem($i->id,0)['desc'],
				's_price'       => $IPrice,
				'price'         => $price,
				'last_price'    => $last_price,
				'count'         => count($count),
				'addon'         => $it->addon($i->id),
				'status'        => $i->status,
				'store'         => $store,
			];
			
 
			return response()->json(['data' => $item]);
		
			
			 
		} catch (\Exception $th) {
			return response()->json(['data' => 'error','error' => $th->getMessage()]);
		}
	}

	public function getStoreWeb($id)
	{ 
		try {
			$store   = new User;
			return response()->json(['data' => $store->getStore($id)]);
		} catch (\Exception $th) {
			return response()->json(['data' => 'error','error' => $th->getMessage()]);
		}
		
	}


	/**
	 * Funciones para eliminacion de datos del usuario
	 */

	public function deleteUserData(Request $request)
	{
		$user_id = $request->get('user_id');

		// Buscamos si el usuario tiene pedidos activos
		$orders = Order::where('user_id', $user_id)->whereIn('status',[0,1,3,4])->count();
		if ($orders > 0) {
			return response()->json([
				'success' => false,
				'code'    => 302,
				'msg'	  => 'orders_active'
			]);
		}

		// Buscamos si el usuario tiene servicios en ruta
		$comms = Commaned::where('user_id', $user_id)->whereIn('status',[0,1,3,4])->count();
		if ($comms > 0) {
			return response()->json([
				'success' => false,
				'code'    => 303,
				'msg'	  => 'service_active'
			]);
		}

		// Eliminamos al usuario
		AppUser::find($user_id)->delete();

		return response()->json([
			'success' => true,
			'code'    => 200,
			'msg'	  => 'user_deleted'
		]);
	}
}
