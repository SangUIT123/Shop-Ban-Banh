<?php

namespace App\Http\Controllers;
use App\Slide;
use App\Product;
use App\ProductType;
use App\Cart;
use Session;
use App\Customer;
use App\Bill;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function getIndex(){
        $slide = Slide::all(); 
        $new_product = Product::where('new',1)->paginate(4);    
        $sanpham_khuyenmai = Product::where('promotion_price','<>',0)->paginate(8);
        return view('page.trangchu',compact('slide','new_product','sanpham_khuyenmai'));

    }
    public function getLoaiSp($type){
        $sp_theoloai = Product::where('id_type',$type)->get();  
        $sanpham_khac = Product::where('id_type','<>',$type)->paginate(6);
        $loai = ProductType::all();
        $loai_sp = Product::where('id',$type)->first();  
        return view('page.loai_sanpham',compact('sp_theoloai','sanpham_khac','loai','loai_sp'));
    }
    public function getChitiet(Request $req){
        $sanpham = Product::where('id',$req->id)->first();  
        $sp_tuongtu = Product::where('id_type',$sanpham->id_type)->paginate(6);
        return view('page.chitiet_sanpham', compact('sanpham','sp_tuongtu'));
    }
    public function getLienhe(){
        return view('page.lienhe');
    }
    public function getGioithieu(){
        return view('page.gioithieu');
    }
    public function getAddtoCart(Request $req,$id){
        $product = Product::find($id);
        $oldCart = Session('cart')?Session::get('cart'):null;
        $cart = new Cart($oldCart);
        $cart->add($product, $id);
        $req->session()->put('cart',$cart);
        return redirect()->back();    
    }
    public function GetDelItemCart($id){
        $oldCart = Session::has('cart')?Session::get('cart'):null;
        $cart = new Cart($oldCart);
        $cart->removeItem($id);
        if(count($cart->items)>0){
            Session::put('cart',$cart);
        }
        else{
            Session::forget('cart');
        }
        
        return redirect()->back(); 

    }
    public function getCheckout(){
        return view('page.dat_hang');
    }

    /*public function postCheckout(Request $req){
        $cart = Session::post('cart');
        
        $customer = new Customer;
        $customer -> name = $req->name;
        $customer -> gender = $req->name;
        $customer -> email = $req->email;
        $customer -> adress = $req->adress;
        $customer -> phone_number = $req->phone_number;
        $customer -> notes = $req->notes;
        //$customer - >save();
        
        $bill = new Bill;
        $bill->id_customer = $customer->id;
        $bill->date_order = date('Y-m-d');
        $bill->total = $cart->totalPrice;
        $bill->payment = $req->payment;
    }*/
}