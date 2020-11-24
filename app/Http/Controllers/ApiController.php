<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use DB;
class ApiController extends Controller
{
    //
    public function register(Request $request)
    {
        $post=$request->all();
        $validator = \Validator::make($request->all(), [
            'email' => 'required|email',
            'name' => 'required|string|max:50',
            'password' => 'required',
            'phone' => 'required|max:10',
        ]);

        if ($validator->fails()) {
            return response()->json(["msg"=>"Invalid Input data"],401);
        }
        $mob=DB::table('user')->where('phone',$post['phone'])->get();
        if(count($mob))
        {
            return response()->json(["msg"=>"Phone no already registered"],401);
        }
        DB::table('user')->insert([
           'name'=>$post['name'],
           'email'=>$post['email'],
           'phone'=>$post['phone'],
           'password'=>$post['password'],
           ]);
        return response()->json(["msg"=>"Registration Successfull"],200);
    }
    public function userlogin(Request $request)
    {
        $post=$request->all();
        $login=DB::table('user')->where('email',$post['email'])->orWhere('phone',$post['email'])->where('password',$post['password'])->get();
        if(count($login)>0)
        {
            $login_data=[];
            $login_data["u_id"]=Crypt::encryptString($login[0]->u_id);
            $login_data["name"]=$login[0]->name;
            $login_data["phone"]=$login[0]->phone;
            $login_data["email"]=$login[0]->email;
            return response()->json($login_data,200);
        }
        else
        {
            return response()->json(["msg"=>"Invalid username or password"],401);
        }
    }
    public function getTopHotel()
    {
        $data=array();
        $hotel=DB::table('hotel')
        ->where('priority','>',0)
        ->where('status',1)
        ->orderBy('priority','DESC')
        ->get();
        for ($o=0; $o < count($hotel); $o++)
        {
            $data[$o]['h_id']=$hotel[$o]->h_id;
            $data[$o]['name']=$hotel[$o]->name;
            $data[$o]['city']=$hotel[$o]->city;
            $data[$o]['address']=$hotel[$o]->address;
            $data[$o]['type']=$hotel[$o]->type;
            $data[$o]['cost']=$hotel[$o]->cost;
            $data[$o]['image']=$hotel[$o]->image;
            $data[$o]['del_charge']=$hotel[$o]->del_charge;
            $data[$o]['status']=ApiController::checkHotelTime($hotel[$o]->h_id);
        }
        usort($data, function($a, $b){return $b['status'] - $a['status'];});
        return response()->json($data,200);
    }
    public function getHotel()
    {
        $data=array();
        $hotel=DB::table('hotel')
        ->orderBy('priority','DESC')
        ->where('hotel.status',1)
        ->get();
        for ($o=0; $o < count($hotel); $o++)
        {
            $timing=DB::table('hotel_time')
                ->where('h_id',$hotel[$o]->h_id)
                ->orderBy('open_at')
                ->select('open_at','close_at')
                ->get();
            $hotel_time="";
            for ($i=0; $i < count($timing); $i++)
            {
            	$hotel_time.= date("gA", strtotime($timing[$i]->open_at))." - ". date("gA", strtotime($timing[$i]->close_at))." , ";
            }
            $data[$o]['h_id']=$hotel[$o]->h_id;
            $data[$o]['name']=$hotel[$o]->name;
            $data[$o]['city']=$hotel[$o]->city;
            $data[$o]['address']=rtrim($hotel[$o]->address,",Gujarat 365601");
            $data[$o]['type']=$hotel[$o]->type;
            $data[$o]['cost']=$hotel[$o]->cost;
            $data[$o]['image']=$hotel[$o]->image;
            $data[$o]['del_charge']=$hotel[$o]->del_charge;
            $data[$o]["time"]=rtrim($hotel_time, ', ');
            $data[$o]['status']=ApiController::checkHotelTime($hotel[$o]->h_id);
        }
        usort($data, function($a, $b){return $b['status'] - $a['status'];});
        return response()->json($data,200);
    }
    public function checkHotelTime($h_id)
    {
        $time = date('H:i:s');
        $hotel=DB::table('hotel_time')
        ->join('hotel','hotel_time.h_id','hotel.h_id')
        ->where("hotel_time.h_id",$h_id)
        ->where('hotel_time.open_at','<',$time)
        ->where('hotel_time.close_at','>',$time)
        ->where('hotel.status',1)->count();
        if($hotel==0)
        {
            return 0;
        }
        return 1;
    }
    public function getCartData(Request $request){
        $post=$request->all();
        $hotelDetail=array();
        $restaurant=DB::table('hotel')
                ->where('h_id',$post['hotelId'])
                ->select('name','h_id','address','del_charge')
                ->get();
        if(count($restaurant)>0){
        	$hotelDetail["name"]=$restaurant[0]->name;
        	$hotelDetail["address"]=$restaurant[0]->address;
        	$hotelDetail["del_charge"]=$restaurant[0]->del_charge;
        	$cat=DB::table('hotel_menu')
                ->join('menu','hotel_menu.m_id','=','menu.m_id')
                ->whereIn('hotel_menu.hm_id',$post['menuId'])
                ->get();
            $userAddress= $user_addresses=DB::table('user_address')->where('u_id','=',Crypt::decryptString($post['uId']))->get();
            
            $hotelDetail["userAddress"]=$userAddress;
            $hotelDetail["hotel_menu"]=$cat;
        }
        return response()->json($hotelDetail,200);
    }
    public function getHotelMenuApp($id)
    {
        $hotelDetail=array();

        $restaurant=DB::table('hotel')
                ->where('h_id',$id)
                ->select('name','h_id','address','cost','status')
                ->get();
        if(count($restaurant)>0){
        	$hotelDetail["name"]=$restaurant[0]->name;
        	$hotelDetail["address"]=$restaurant[0]->address;
        	$hotelDetail["cost"]=$restaurant[0]->cost;
        	$hotelDetail["status"]=ApiController::checkHotelTime($restaurant[0]->h_id);

        	$timing=DB::table('hotel_time')
                ->where('hotel_time.h_id',$id)
                ->select('open_at','close_at')
                ->get();
            $hotel_time="";
            for ($o=0; $o < count($timing); $o++)
            {
            	$hotel_time.= date("gA", strtotime($timing[$o]->open_at))." - ". date("gA", strtotime($timing[$o]->close_at))." , ";
            }
            $hotelDetail["time"]=rtrim($hotel_time, ', ');
            $hotelDetail["deliveryTime"]="20 MIN";
            
            $cat=DB::table('hotel_menu')
                ->join('menu','hotel_menu.m_id','=','menu.m_id')
                ->join('category','menu.c_id','category.c_id')
                ->where('hotel_menu.h_id',$id)
                ->select('category.name as category','category.c_id')
                ->groupBy('category.name')
                ->get();
            for ($o=0; $o < count($cat); $o++)
            {
            	$temp_menu=array();
            	$temp_menu["name"]=$cat[$o]->category;
                $arr=array();
            	$m_data=DB::table('hotel_menu')
        		        ->join('menu','hotel_menu.m_id','=','menu.m_id')
        		        ->where('hotel_menu.h_id',$id)
        		        ->where('menu.c_id',$cat[$o]->c_id)
        		        ->select('hotel_menu.hm_id','hotel_menu.image','hotel_menu.price','hotel_menu.sell_price','menu.name','hotel_menu.type')
        		        ->get();
        		for ($md=0; $md < count($m_data); $md++)
            	{
            		$arr[$md]=$m_data[$md];
            	}
            	$temp_menu["menuData"]=$arr;
            	$hotelDetail["menu"][$o]=$temp_menu;
            }
        }
        return response()->json($hotelDetail,200);
    }
    public function getHomeCategory()
    {
        $category=DB::table('category')->get();
        return response()->json($category,200);
    }
    
    public function getHomeCategoryItem($cid)
    {
        $time = date('H:i:s');
        $data=array();
        $hotel=DB::table('category')
        ->join('menu','category.c_id','menu.c_id')
        ->join('hotel_menu','menu.m_id','hotel_menu.m_id')
        ->join('hotel_time','hotel_menu.h_id','hotel_time.h_id')
        ->join('hotel','hotel_time.h_id','hotel.h_id')
        ->where('hotel.status',1)
        ->groupBy('hotel.h_id')
        ->where('category.c_id',$cid)
        ->select('hotel.h_id','hotel.name','hotel.city','hotel.address','hotel.type','hotel.cost','hotel.image','hotel.del_charge')
        ->get();
        for ($o=0; $o < count($hotel); $o++)
        {
            $timing=DB::table('hotel_time')
                ->where('hotel_time.h_id',$hotel[$o]->h_id)
                ->select('open_at','close_at')
                ->get();
            $hotel_time="";
            for ($h=0; $h < count($timing); $h++)
            {
            	$hotel_time.= date("gA", strtotime($timing[$h]->open_at))." - ". date("gA", strtotime($timing[$h]->close_at))." , ";
            }
            $data[$o]['h_id']=$hotel[$o]->h_id;
            $data[$o]['name']=$hotel[$o]->name;
            $data[$o]['city']=$hotel[$o]->city;
            $data[$o]['address']=$hotel[$o]->address;
            $data[$o]['type']=$hotel[$o]->type;
            $data[$o]['cost']=$hotel[$o]->cost;
            $data[$o]['image']=$hotel[$o]->image;
            $data[$o]['del_charge']=$hotel[$o]->del_charge;
            $data[$o]["time"]=rtrim($hotel_time, ', ');
            $data[$o]['status']=ApiController::checkHotelTime($hotel[$o]->h_id);
        }
        usort($data, function($a, $b){return $b['status'] - $a['status'];});
        return response()->json($data,200);
        
    }
    public function appSearch($query)
    {
        $result=DB::table('menu')
        ->  join('hotel_menu','menu.m_id','=','hotel_menu.m_id')
        ->join('category','menu.c_id','=','category.c_id')
        ->join('hotel','hotel_menu.h_id','=','hotel.h_id')
        ->where('menu.name','like','%'.$query.'%')
        ->orWhere('category.name','like','%'.$query.'%')
        ->orWhere('hotel.name','like','%'.$query.'%')
        ->select('hotel.h_id','menu.name as menu','category.name as category','hotel.name as hotel','hotel_menu.price')
        ->get();
        return response()->json($result,200);
    }
    public function saveUserAddress(Request $request)
    {
        $post=$request->all();
        $validator = \Validator::make($request->all(), [
            'u_id' => 'required',
            'name' => 'required',
            'phone' => 'required',
            'address' => 'required',
            'house_no' => 'required',
            'landmark' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(["msg"=>"Invalid Input data"],401);
        }
        DB::table('user_address')->insert([
           'u_id'=>Crypt::decryptString($post['u_id']),
           'phone'=>$post['phone'],
           'name'=>$post['name'],
           'address'=>$post['address'],
           'house_no'=>$post['house_no'],
           'landmark'=>$post['landmark']
           ]);
        return response()->json(["msg"=>"Address Saved Successfull"],200);
    }
    public function getUserAddress($id)
    {
        $user_addresses=DB::table('user_address')
        ->where('u_id','=',Crypt::decryptString($id))
        ->get();
        return response()->json($user_addresses);
    }
    public function placeOrder(Request $request)
    {
        $post=$request->all();
        $mrp_sell=array();
        foreach($post['hm_id'] as $key => $s)
        {
            $menu_price=DB::table('hotel_menu')->where('hm_id',$post['hm_id'][$key])->select('price','sell_price')->get();
            $mrp_sell['price'][$key]=$menu_price[0]->price;
            $mrp_sell['sell_price'][$key]=$menu_price[0]->sell_price;
        }
        try
        {
            $o_id=DB::table('order_master')->insertGetId([
                "u_id"=>Crypt::decryptString($post['u_id']),
                "a_id"=>$post['a_id'],
                "h_id"=>$post['h_id'],
                "total"=>$post['total'],
                "discount"=>$post['discount'],
                "del_charge"=>$post['del_charge'],
            ]);
            foreach($post['hm_id'] as $key => $s)
            {
                DB::table('order_detail')->insert([
                    "o_id"=>$o_id,
                    "hm_id"=>$post['hm_id'][$key],
                    "qty"=>$post['qty'][$key],
                    "price"=>$mrp_sell['price'][$key],
                    "sell_price"=>$mrp_sell['sell_price'][$key]
                ]);
            }
            $customerName=DB::table('user_address')->where('a_id',$post['a_id'])->value('name');  
            $hotelName=DB::table('hotel')->where('h_id',$post['h_id'])->value('name');  
            ApiController::msgSend($customerName,$hotelName);
            return response()->json(["msg"=>"Order Placed Successfully"],200);
        }catch(Exception $e)
        {
            return response()->json(["msg"=>"Can't Place Order"],401);
        }
    }
    
    public function getUserOrder($u_id)
    {
        $order=DB::table('order_master')
        ->join('hotel','order_master.h_id','=','hotel.h_id')
        ->join('status_detail','order_master.status','=','status_detail.s_id')
        ->where('order_master.u_id',Crypt::decryptString($u_id))
        ->orderBy('order_master.date','DESC')
        ->select('hotel.name as hotel','hotel.address as address','order_master.o_id','order_master.a_id','order_master.total','order_master.discount','order_master.del_charge','status_detail.status_name','order_master.date')
        ->get();
        $menu=array();
        for ($o=0; $o < count($order); $o++)
        {
            $order_menu=DB::table('order_detail')
            ->join('hotel_menu','order_detail.hm_id','=','hotel_menu.hm_id')
            ->join('menu','hotel_menu.m_id','=','menu.m_id')
            ->where('order_detail.o_id',$order[$o]->o_id)
            ->select('menu.name as menu','order_detail.qty','order_detail.price')
            ->get();
            
            $menu[$o]['hotelName']=$order[$o]->hotel;
            $menu[$o]['status_name']=$order[$o]->status_name;
            $menu[$o]['hotelAddress']=$order[$o]->address;
            $menu[$o]['oid']=$order[$o]->o_id;
            $menu[$o]['total']=($order[$o]->total+$order[$o]->del_charge-$order[$o]->discount);
            $menu[$o]['date']=date("M j,Y,g:i A", strtotime($order[$o]->date));
            $str="";
            for ($i=0; $i < count($order_menu); $i++)
            {
                $str.=$order_menu[$i]->menu."(".$order_menu[$i]->qty.") ,";
            }
            $menu[$o]['menuList']=rtrim($str, ',');
        }
        return response()->json($menu);
    }
    
    
    public function getOrderDetail($oid)
    {
        $order=DB::table('order_master')
        ->join('hotel','order_master.h_id','=','hotel.h_id')
        ->join('status_detail','order_master.status','=','status_detail.s_id')
        ->where('order_master.o_id',$oid)
        ->orderBy('order_master.date','DESC')
        ->select('hotel.name as hotel','hotel.address as address','order_master.o_id','order_master.a_id','order_master.total','order_master.discount','order_master.del_charge','status_detail.status_name','order_master.date','order_master.a_id')
        ->get();
        $menu=array();
        for ($o=0; $o < count($order); $o++)
        {
            $order_menu=DB::table('order_detail')
            ->join('hotel_menu','order_detail.hm_id','=','hotel_menu.hm_id')
            ->join('menu','hotel_menu.m_id','=','menu.m_id')
            ->where('order_detail.o_id',$order[$o]->o_id)
            ->select('menu.name','order_detail.qty','order_detail.price')
            ->get();
            
            $user_address=DB::table('user_address')
            ->where('user_address.a_id',$order[$o]->a_id)
            ->select('house_no','landmark','address')
            ->get();
            
            $menu['hotelName']=$order[$o]->hotel;
            $menu['status_name']=$order[$o]->status_name;
            $menu['hotelAddress']=$order[$o]->address;
            $menu['oid']=$order[$o]->o_id;
            $menu['total']=($order[$o]->total+$order[$o]->del_charge-$order[$o]->discount);
            $menu['del_charge']=$order[$o]->del_charge;
            $menu['discount']=$order[$o]->discount;
            $menu['date']=date("M j,Y,g:i A", strtotime($order[$o]->date));
            $menu['user_address']=$user_address[0]->house_no.','.$user_address[0]->landmark.','.$user_address[0]->address;
            
            
            $items=array();
            for ($i=0; $i < count($order_menu); $i++)
            {
                $item_data=array();
                $item_data["name"]=$order_menu[$i]->name;
                $item_data["qty"]=$order_menu[$i]->qty;
                $item_data["price"]=$order_menu[$i]->price;
                $items[$i]= $item_data;
            }
            $menu['items']=$items;
            
        }
        return response()->json($menu);
    }
    public function getVersionCode(){
        $data["version"]=DB::table('constant')->where('NAME', 'APP_VERSION')->value('VALUE');
        return response()->json($data,200);
    }
    
    //Admin App
    public function getPendingOrder()
    {
        $pending=DB::table('order_master')
        ->join('hotel','order_master.h_id','hotel.h_id')
        ->join('user_address','order_master.a_id','user_address.a_id')
        ->join('status_detail','order_master.status','status_detail.s_id')
        ->where('order_master.status','=',1)
        ->orderBy('order_master.date','DESC')
        ->select('order_master.o_id','order_master.date','user_address.name','user_address.phone','user_address.address','user_address.house_no','user_address.landmark','hotel.name as hotel','hotel.address as hotel_address','hotel.phone as hotel_phone','status_detail.status_name')
        ->get();
        return response()->json($pending);
    }
    public function getRider()
    {
        $data=array();
        $data["riders"]=DB::table('rider')->select('r_id','name','phone')->get();
        $data["pendingOrderCount"]=DB::table('order_master')->where('status',1)->count();
        $data["orderCount"]=DB::table('order_master')->count();
        
        return response()->json($data);
    }
    public function getAllOrder()
    {
        $all=DB::table('order_master')
        ->join('hotel','order_master.h_id','hotel.h_id')
        ->join('user_address','order_master.a_id','user_address.a_id')
        ->join('status_detail','order_master.status','status_detail.s_id')
        ->join('rider','order_master.r_id','rider.r_id')
        ->orderBy('order_master.date','DESC')
        ->select('order_master.o_id','order_master.date','rider.name as riderName','user_address.name','user_address.phone','user_address.address','user_address.house_no','user_address.landmark','hotel.name as hotel','hotel.address as hotel_address','hotel.phone as hotel_phone','status_detail.status_name')
        ->paginate(50);
        return response()->json($all->items());
    }
    public function setRider(Request $request)
    {
        $post=$request->all();
        DB::table('order_master')->where('o_id',$post['o_id'])->update(["r_id"=>$post['r_id'],"status"=>2]);
        return response()->json(["msg"=>"Update Successfully"]);
    }
    
    
    public function getAdminOrderDetail($oid)
    {
        $order=DB::table('order_master')
        ->join('hotel','order_master.h_id','=','hotel.h_id')
        ->join('status_detail','order_master.status','=','status_detail.s_id')
        ->where('order_master.o_id',$oid)
        ->orderBy('order_master.date','DESC')
        ->select('hotel.name as hotel','hotel.address as address','order_master.r_id','order_master.o_id','order_master.a_id','order_master.total','order_master.discount','order_master.del_charge','status_detail.status_name','order_master.date','order_master.a_id')
        ->get();
        $menu=array();
        if(count($order)==1)
        {
            $order_menu=DB::table('order_detail')
            ->join('hotel_menu','order_detail.hm_id','=','hotel_menu.hm_id')
            ->join('menu','hotel_menu.m_id','=','menu.m_id')
            ->where('order_detail.o_id',$order[0]->o_id)
            ->select('menu.name','order_detail.qty','order_detail.price','hotel_menu.type')
            ->get();
            
            $user_address=DB::table('user_address')
            ->where('user_address.a_id',$order[0]->a_id)
            ->select('name','house_no','landmark','address','phone')
            ->get();
            
            $riderName="";
            if($order[0]->r_id!=0){
                $riderName=DB::table('rider')->where('r_id',$order[0]->r_id)->value('name');  
            }
            
            $menu['deleverdBy']=$riderName;
            $menu['hotelName']=$order[0]->hotel;
            $menu['status_name']=$order[0]->status_name;
            $menu['hotelAddress']=$order[0]->address;
            $menu['oid']=$order[0]->o_id;
            $menu['total']=($order[0]->total+$order[0]->del_charge-$order[0]->discount);
            $menu['del_charge']=$order[0]->del_charge;
            $menu['discount']=$order[0]->discount;
            $menu['date']=date("M j,Y,g:i A", strtotime($order[0]->date));
            
            $menu['user_address']=$user_address[0]->house_no.','.$user_address[0]->landmark.','.$user_address[0]->address;
            $menu['user_name']=$user_address[0]->name;
            $menu['user_call']=$user_address[0]->phone;
            
            
            $items=array();
            for ($i=0; $i < count($order_menu); $i++)
            {
                $item_data=array();
                $item_data["name"]=$order_menu[$i]->name;
                if(!empty(trim($order_menu[$i]->type))){
                    $item_data["name"]=$order_menu[$i]->name." (".$order_menu[$i]->type.")";
                }
                $item_data["qty"]=$order_menu[$i]->qty;
                $item_data["price"]=$order_menu[$i]->price;
                $items[$i]= $item_data;
            }
            $menu['items']=$items;
            
        }
        return response()->json($menu);
    }
    
    public function msgSend($cname,$hname)
    {
        define( 'API_ACCESS_KEY', 'AAAA----FE6F' );
    
        $data = array("to" => "/topics/orderAlert","notification" => array( "title" => $cname, "body" => $hname,"sound" => "loud_warning"));                       
        $data_string = json_encode($data); 
        
        $headers = array
        (
             'Authorization: key=' .'AAAAl172EUs:APA91bGD4TcxIktb3Zxu8xLqCkHx6H9Fw0FPaOabSIIX5YBNL40KNu2dymtQWVUi7p-3Zp8tCfxE8QF5NYDcksJ1wJFK-lSCQVyJF29RVsSQPjMMOfjVyR0EhXL1yR5iQ-VvmWVMYLSu', 
             'Content-Type: application/json'
        );                                                                                 
        $ch = curl_init();  
        
        curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );                                                                  
        curl_setopt( $ch,CURLOPT_POST, true );  
        curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
        curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $ch,CURLOPT_POSTFIELDS, $data_string);     
        $result = curl_exec($ch);
        
        curl_close ($ch);
    }
    
    
}
