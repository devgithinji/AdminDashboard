<?php

namespace App\Http\Controllers;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Session;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function login(Request $request)
    {
       if ($request->isMethod('post'))
       {
           $data = $request->input();

           if (Auth::attempt(['email'=>$data['email'],'password'=>$data['password'],'admin'=>'1']))
           {
              // echo "success"; die();
               return redirect('/admin/dashboard');
           }
           else
           {
              return redirect("/admin")->with('flash_message_error','Invalid Username or Password');
           }
       }
      return view('admin.admin_login');
    }

    public function dashboard()
    {
        //echo "test"; die();
        return view('admin.dashboard');
    }

    public function settings()
    {
        return view('admin.settings');
    }

    public  function chkPassword(Request $request)
    {
        $data = $request->all();
        $current_password = $data['current_pwd'];
        $check_password = User::where(['admin'=>'1'])->first();
        if (Hash::check($current_password,$check_password->password))
        {
            echo "true"; die;
        }
        else
        {
            echo "false"; die;
        }
    }

    public function updatePassword(Request $request)
    {
        if ($request->isMethod('post'))
        {
            $data = $request->all();
            //echo "<pre>"; print_r($data); die();
            $check_password = User::where(['email'=>Auth::user()->email])->first();
            $current_password = $data['current_pwd'];
            if (Hash::check($current_password,$check_password->password))
            {
                $password = bcrypt($data['new_pwd']);
                User::where('id','1')->update(['password'=>$password]);
                return redirect('/admin/settings')->with('flash_message_success','Password updated Successfully!!');
            }
            else{
                return redirect('/admin/settings')->with('flash_message_error','Incorrect Current Password!!');
            }
        }
    }
    public function  logout()
    {
       Session::flush();
        return redirect("/admin")->with('flash_message_success','Logged out successfully');
    }

}
