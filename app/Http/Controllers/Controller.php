<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use DB;
use Session;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    public function login()
    {
        return view('login');
    }
    public function checkLogin(Request $request)
    {
        $post=$request->all();
        $login=DB::table('admin')->where('email',$post['email'])->where('password',$post['password'])->get();
        if(count($login)>0)
        {
            $request->session()->put('Admin',$login[0]->email);
            return redirect('/');
        }
        \Session::flash('error','Invalid Username or Password');
        return redirect('/Login');
    }
    public function getRiderLogin()
    {
        return view('riderLogin');
    }
    public function checkRiderLogin(Request $request)
    {
        $post=$request->all();
        $login=DB::table('rider')->where('phone',$post['phone'])->where('password',$post['password'])->get();
        if(count($login)>0)
        {
            $request->session()->put('Rider',$login[0]->r_id);
            return redirect('/rider');
        }
        \Session::flash('error','Invalid Username or Password');
        return redirect('/riderLogin');
    }
    public function chagePassword()
    {
        return view('changepassword');
    }
    public function updateAdminPassword(Request $request)
    {
        $post=$request->all();
        $check=DB::table('admin')->where('password',$post['oldpass'])->get();
        if(count($check)>0)
        {
            if($post['newpass']==$post['cnfpass'])
            {
                DB::table('admin')->update(['password'=>$post['cnfpass']]);
                \Session::flash('success','Password Updated Successfully');
                return redirect("/Login");
            }
            else
            {
                \Session::flash('error',"Confirm Password Doesn't match");
            }
        }
        else
        {
            \Session::flash('error',"Wrong Old Password");
        }
        return redirect("/chagePassword");
    }
    public function logout(Request $request)
    {
        $request->session()->forget('Admin');
        return redirect('/Login');
    }
    public function getDashoard()
    {
        $pending=DB::table('order_master')
        ->join('hotel','order_master.h_id','hotel.h_id')
        ->join('user_address','order_master.a_id','user_address.a_id')
        ->join('status_detail','order_master.status','status_detail.s_id')
        ->where('order_master.status','<',5)
        ->orderBy('order_master.date','DESC')
        ->select('order_master.o_id','order_master.date','user_address.name','user_address.phone','user_address.address','user_address.house_no','user_address.landmark','hotel.name as hotel','hotel.address as hotel_address','status_detail.status_name')
        ->get();
        return view('welcome')->with('pending',$pending);
    }
    public function getOrderDetail($id)
    {
        $order=DB::table('order_master')
        ->join('hotel','order_master.h_id','hotel.h_id')
        ->join('user_address','order_master.a_id','user_address.a_id')
        ->join('status_detail','order_master.status','status_detail.s_id')
        ->leftjoin('rider','order_master.r_id','rider.r_id')
        ->where('order_master.o_id',$id)
        ->select('order_master.o_id','order_master.total','order_master.discount','order_master.del_charge','order_master.status','order_master.date','user_address.name','user_address.phone','user_address.address','user_address.house_no','user_address.landmark','hotel.name as hotel','hotel.address as hotel_address','status_detail.status_name','rider.name as rider','rider.phone as rider_phone')
        ->get();
        $item=DB::table('order_detail')
        ->join('hotel_menu','order_detail.hm_id','hotel_menu.hm_id')
        ->join('menu','hotel_menu.m_id','menu.m_id')
        ->where('order_detail.o_id',$id)
        ->select('menu.name as menu','order_detail.qty','order_detail.price','order_detail.sell_price')
        ->get();
        //$status_detail=DB::table('status_detail')->get();

        return view('orderDetail')->with('order',$order)->with('item',$item);
    }
    public function getAllOrders()
    {
        $all=DB::table('order_master')
        ->join('hotel','order_master.h_id','hotel.h_id')
        ->join('user_address','order_master.a_id','user_address.a_id')
        ->join('status_detail','order_master.status','status_detail.s_id')
        ->orderBy('order_master.date','DESC')
        ->select('order_master.o_id','order_master.date','user_address.name','user_address.phone','user_address.address','user_address.house_no','user_address.landmark','hotel.name as hotel','hotel.address as hotel_address','status_detail.status_name')
        ->get();
        return view('allOrder')->with('all',$all);
    }
    public function getHotelDetail()
    {
        $hotel=DB::table('hotel')->get();
        return view('hotel')->with('hotel',$hotel);
    }
    public function getAddHotel()
    {
        return view('addHotel');
    }
    public function addHotel(Request $request)
    {
        $post=$request->all();
        $file=$request->file('image');
        $filenm=rand(0000,9999).$file->getClientOriginalName();
        $file->move('public/hotel',$filenm);

        $hotel_id=DB::table('hotel')->insertGetId([
            "name"=>$post['name'],
            "address"=>$post['address'],
            "city"=>$post['city'],
            "phone"=>$post['phone'],
            "cost"=>$post['cost'],
            "del_charge"=>$post['del_charge'],
            "type"=>$post['type'],
            "image"=>$filenm
        ]);
        foreach($post['open'] as $key => $s)
        {
            if($post['open'][$key]>0)
            {
                DB::table('hotel_time')->insert([
                    "h_id"=>$hotel_id,
                    "open_at"=>$post['open'][$key],
                    "close_at"=>$post['close'][$key]
                ]);
            }
        }
        \Session::flash('success','Hotel Added Successfully');
        return redirect('/Hotel');
    }
    public function updateHotel($id)
    {
        $hotel=DB::table('hotel')->where('h_id',$id)->get();
        $hotel_time=DB::table('hotel_time')->where('h_id',$id)->get();
        return view('addHotel')->with('hotel',$hotel)->with('hotel_time',$hotel_time);
    }
    public function updateHotelAdmin(Request $request)
    {

        $post=$request->all();
        $filenm=$post['old_image'];
        if($request->hasFile('image'))
        {
            $file=$request->file('image');
            $filenm=rand(0000,9999).$file->getClientOriginalName();
            $file->move('public/hotel',$filenm);
        }
        DB::table('hotel')->where('h_id',$post['h_id'])->update([
            "name"=>$post['name'],
            "address"=>$post['address'],
            "city"=>$post['city'],
            "phone"=>$post['phone'],
            "cost"=>$post['cost'],
            "del_charge"=>$post['del_charge'],
            "type"=>$post['type'],
            "image"=>$filenm
        ]);
        DB::table('hotel_time')->where('h_id',$post['h_id'])->delete();
        foreach($post['open'] as $key => $s)
        {
            if($post['open'][$key]>0)
            {
                DB::table('hotel_time')->insert([
                    "h_id"=>$post['h_id'],
                    "open_at"=>$post['open'][$key],
                    "close_at"=>$post['close'][$key]
                ]);
            }
        }
        \Session::flash('success','Hotel Updated Successfully');
        return redirect('/Hotel');
    }
    public function getCategory()
    {
        $category=DB::table('category')->get();
        return view('category')->with('category',$category);
    }
    public function addCategory(Request $request)
    {
        $post=$request->all();
        $file=$request->file('image');
        $filenm=rand(0000,9999).$file->getClientOriginalName();
        $file->move('public/category',$filenm);
        DB::table('category')->insert([
            "name"=>$post['name'],
            "image"=>$filenm
        ]);
        \Session::flash('success','Category Added Successfully');
        return redirect('/Category');
    }
    public function updateCategory($id)
    {
        $category=DB::table('category')->get();
        $up_cat=DB::table('category')->where('c_id',$id)->get();
        return view('category')->with('category',$category)->with('up_cat',$up_cat);
    }
    public function updateCategoryAdmin(Request $request)
    {
        $post=$request->all();
        $filenm=$post['old_image'];
        if($request->hasFile('image'))
        {
            $file=$request->file('image');
            $filenm=rand(0000,9999).$file->getClientOriginalName();
            $file->move('public/category',$filenm);
        }
        DB::table('category')->where('c_id',$post['c_id'])->update([
            "name"=>$post['name'],
            "image"=>$filenm
        ]);
        \Session::flash('success','Category Updated Successfully');
        return redirect('/Category');
    }
    public function getMenu()
    {
        $menu=DB::table('menu')
        ->join('category','menu.c_id','category.c_id')
        ->select('menu.m_id','menu.name as mname','category.name as cname')
        ->get();
        $category=DB::table('category')->get();
        return view('menu')->with('menu',$menu)->with('category',$category);
    }
    public function addMenu(Request $request)
    {
        $post=$request->all();
        DB::table('menu')->insert([
            "c_id"=>$post['category'],
            "name"=>$post['name'],
        ]);
        \Session::flash('success','Menu Added Successfully');
        return redirect('/Menu');
    }
    public function updateMenu($id)
    {
        $menu=DB::table('menu')
        ->join('category','menu.c_id','category.c_id')
        ->select('menu.m_id','menu.name as mname','category.name as cname')
        ->get();
        $category=DB::table('category')->get();
        $up_menu=DB::table('menu')->where('m_id',$id)->get();
        return view('menu')->with('menu',$menu)->with('up_menu',$up_menu)->with('category',$category);
    }
    public function updateMenuAdmin(Request $request)
    {
        $post=$request->all();
        DB::table('menu')->where('m_id',$post['m_id'])->update([
            "c_id"=>$post['category'],
            "name"=>$post['name'],
        ]);
        \Session::flash('success','Menu Updated Successfully');
        return redirect('/Menu');
    }
    public function getHotelMenu()
    {
        $menu=DB::table('menu')->get();
        $hotel=DB::table('hotel')->select('h_id','name','city')->get();
        return view('hotelMenu')->with('menu',$menu)->with('hotel',$hotel);
    }
    public function addHotelMenu(Request $request)
    {
        $post=$request->all();
        $file=$request->file('image');
        $filenm=rand(0000,9999).$file->getClientOriginalName();
        $file->move('public/menu',$filenm);
        DB::table('hotel_menu')->insert([
            "m_id"=>$post['menu'],
            "h_id"=>$post['hotel'],
            "type"=>$post['type'],
            "image"=>$filenm,
            "price"=>$post['price'],
            "sell_price"=>$post['sell_price'],
        ]);
        \Session::flash('success','Menu Added Successfully');
        return redirect('/hotelMenu');
    }
    public function updateHotelMenu($id)
    {
        $update=DB::table('hotel_menu')->where('hm_id',$id)->get();
        $menu=DB::table('menu')->get();
        $hotel=DB::table('hotel')->select('h_id','name','city')->get();
        return view('hotelMenu')->with('menu',$menu)->with('hotel',$hotel)->with('update',$update);
    }
    public function updateHotelMenuAdmin(Request $request)
    {
        $post=$request->all();
        $filenm=$post['old_image'];
        if($request->hasFile('image'))
        {
            $file=$request->file('image');
            $filenm=rand(0000,9999).$file->getClientOriginalName();
            $file->move('public/menu',$filenm);
        }
        DB::table('hotel_menu')->where('hm_id',$post['hm_id'])->update([
            "m_id"=>$post['menu'],
            "h_id"=>$post['hotel'],
            "image"=>$filenm,
            "type"=>$post['type'],
            "price"=>$post['price'],
            "sell_price"=>$post['sell_price'],
        ]);
        \Session::flash('success','Menu Updated Successfully');
        return redirect('/hotelMenu');
    }
    public function deleteHotelMenu($id)
    {
        $ch=DB::table('hotel_menu')
        ->join('order_detail','hotel_menu.hm_id','=','order_detail.hm_id')
        ->where('hotel_menu.hm_id',$id)
        ->count();
        if($ch>0)
        {
            \Session::flash('error',"You can't delete this hotel menu");
            return redirect('/hotelMenu');
        }
        DB::table('hotel_menu')->where('hm_id',$id)->delete();
        \Session::flash('success','Menu Deleted Successfully');
        return redirect('/hotelMenu');
    }
    public function getHotelList($id)
    {
        $hotel=DB::table('hotel')->select('h_id','name','city')->get();
        $menu=DB::table('menu')
        ->join('hotel_menu','menu.m_id','hotel_menu.m_id')
        ->join('hotel','hotel_menu.h_id','hotel.h_id')
        ->join('category','menu.c_id','category.c_id')
        ->where('hotel.h_id',$id)
        ->select('hotel.name as h_name','hotel_menu.hm_id','hotel_menu.price','hotel_menu.type','hotel_menu.sell_price','hotel_menu.image','menu.name as mname','category.name as cname')
        ->get();
        return view('hotelList')->with('menu',$menu)->with('hotel',$hotel);
    }


    //rider
    public function getRiderDetail()
    {
        $pending=DB::table('order_master')
        ->join('hotel','order_master.h_id','hotel.h_id')
        ->join('user_address','order_master.a_id','user_address.a_id')
        ->join('status_detail','order_master.status','status_detail.s_id')
        ->where('order_master.status',1)
        ->where('order_master.r_id','!=',Session::get('Rider'))
        ->orderBy('order_master.date','DESC')
        ->select('order_master.o_id','order_master.total','order_master.discount','order_master.del_charge','order_master.status','order_master.date','user_address.name','user_address.phone','user_address.address','user_address.house_no','user_address.landmark','hotel.name as hotel','hotel.address as hotel_address','status_detail.status_name')
        ->get();
        $orders=DB::table('order_master')
        ->join('hotel','order_master.h_id','hotel.h_id')
        ->join('user_address','order_master.a_id','user_address.a_id')
        ->join('status_detail','order_master.status','status_detail.s_id')
        ->where('order_master.status','<','6')
        ->where('order_master.r_id','=',Session::get('Rider'))
        ->orderBy('order_master.date','DESC')
        ->select('order_master.o_id','order_master.total','order_master.discount','order_master.del_charge','order_master.status','order_master.date','user_address.name','user_address.phone','user_address.address','user_address.house_no','user_address.landmark','hotel.name as hotel','hotel.address as hotel_address','status_detail.status_name')
        ->get();
        return view('riderDashboard')->with('pending',$pending)->with('orders',$orders);
    }
    public function getriderOrderDetail($id)
    {
        $order=DB::table('order_master')
        ->join('hotel','order_master.h_id','hotel.h_id')
        ->join('user_address','order_master.a_id','user_address.a_id')
        ->join('status_detail','order_master.status','status_detail.s_id')
        ->where('order_master.o_id',$id)
        ->select('order_master.o_id','order_master.total','order_master.discount','order_master.del_charge','order_master.status','order_master.date','user_address.name','user_address.phone','user_address.address','user_address.house_no','user_address.landmark','hotel.name as hotel','hotel.address as hotel_address','status_detail.status_name')
        ->get();
        if($order[0]->status>1)
        {
            $chk=DB::table('order_master')->where('o_id',$id)->where('r_id',Session::get('Rider'))->count();
            if($chk==0)
            {
                return redirect('/rider');
            }
        }
        $item=DB::table('order_detail')
        ->join('hotel_menu','order_detail.hm_id','hotel_menu.hm_id')
        ->join('menu','hotel_menu.m_id','menu.m_id')
        ->where('order_detail.o_id',$id)
        ->select('menu.name as menu','order_detail.qty','order_detail.price','order_detail.sell_price')
        ->get();
        $status_detail=DB::table('status_detail')->get();
        return view('riderOrderDetail')->with('order',$order)->with('item',$item)->with('status_detail',$status_detail);
    }
    public function chagePasswordRider()
    {
        return view('changepasswordrider');
    }
    public function updateRiderPassword(Request $request)
    {
        $post=$request->all();
        $check=DB::table('rider')->where('password',$post['oldpass'])->get();
        if(count($check)>0)
        {
            if($post['newpass']==$post['cnfpass'])
            {
                DB::table('rider')->update(['password'=>$post['cnfpass']]);
                \Session::flash('success','Password Updated Successfully');
                return redirect("/Login");
            }
            else
            {
                \Session::flash('error',"Confirm Password Doesn't match");
            }
        }
        else
        {
            \Session::flash('error',"Wrong Old Password");
        }
        return redirect("/chagePassword");
    }
    public function logoutRider(Request $request)
    {
        $request->session()->forget('Rider');
        return redirect('/riderLogin');
    }

    //Ajax
    public static function getHotelMenuDetail($id)
    {
        $menu=DB::table('hotel_menu')
        ->join('menu','hotel_menu.m_id','menu.m_id')
        ->join('category','menu.c_id','category.c_id')
        ->where('hotel_menu.h_id',$id)
        ->select('hotel_menu.hm_id','hotel_menu.price','hotel_menu.type','hotel_menu.sell_price','hotel_menu.image','menu.name as mname','category.name as cname')
        ->get();
        return $menu;
    }
    public function changeOrderStatus(Request $request)
    {
        $get=$request->all();
        $ch=DB::table('order_master')->where('o_id',$get['o_id'])->where('status',1)->get();
        if($get['status']==2)
        {
            if(count($ch)==1)
            {
                DB::table('order_master')->where('o_id',$get['o_id'])->update(['status'=>$get['status'],"r_id"=>Session::get('Rider')]);
                return response()->json(["status"=>true]);
            }
            else
            {
                return response()->json(["status"=>false]);
            }
        }
        else
        {
            DB::table('order_master')->where('o_id',$get['o_id'])->update(['status'=>$get['status']]);
        }
    }
    public function changeHotelStatus(Request $request)
    {
        $get=$request->all();
        DB::table('hotel')->where('h_id',$get['h_id'])->update(['status'=>$get['status']]);
        return response()->json(["status"=>true]);
    }
    public function getPendingOrderCount()
    {
        $count=DB::table('order_master')->where('status',1)->count();
        return response()->json($count);
    }
    public function getPendingOrderCountRider()
    {
        $count=DB::table('order_master')->where('status',2)->count();
        return response()->json($count);
    }
    public function getPendingOrderDetail()
    {
        $count=DB::table('order_master')
        ->join('user_address','order_master.u_id','user_address.u_id')
        ->where('order_master.status',1)
        ->orderBy('order_master.date','DESC')
        ->groupBy('order_master.o_id')
        ->select('user_address.name','order_master.o_id','order_master.date')
        ->get();
        return response()->json($count);
    }
    public function getPendingOrderDetailRider()
    {
        $count=DB::table('order_master')
        ->join('user_address','order_master.u_id','user_address.u_id')
        ->where('order_master.status',2)
        ->orderBy('order_master.date','DESC')
        ->groupBy('order_master.o_id')
        ->select('user_address.name','order_master.o_id','order_master.date')
        ->get();
        return response()->json($count);
    }
}
