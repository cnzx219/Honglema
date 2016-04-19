<?php
/**
 * Created by IntelliJ IDEA.
 * User: 王得屹
 * Date: 2016/4/19
 * Time: 11:23
 */
namespace App\Http\Controllers;

use Validator;
use Hash;
use Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use App\User;

class UserController extends Controller{
    //后台用户列表页
    public function index(){
        session_start();
        if(isset($_SESSION['username'])){
            $user = User::paginate(6);
            return view('/cms/user',['users' => $user]);
        }
        else{
            return Redirect::intended('/cms/login.php');
        }
    }
    //后台用户创建页
    public function createUserIndex(){
        session_start();
        if(isset($_SESSION['username'])){
            return view('/cms/user_create');
        }
        else{
            return Redirect::intended('/cms/login.php');
        }
    }
    //创建后台用户
    public function createUser(){
        session_start();
        if(isset($_SESSION['username'])){
            $validator = Validator::make(Input::all(), User::$rules);

            if ($validator->passes()) {
                $user = new User();

                $user->name = Input::get('name');
                $user->email = Input::get('email');
                $password = Input::get('password');
                $passwordConfirm = Input::get('passwordConfirm');
                if(strlen($password) < 8){
                    echo "<script>history.go(-1); alert('密码至少8位!');</script>";
                    return;
                }
                if($password == $passwordConfirm){
                    $user->password = Hash::make($password);
                }
                else{
                    echo "<script>history.go(-1); alert('请输入相同的密码!');</script>";
                    return;
                }
                $user->is_admin = Input::get('is_admin');

                $user->save();

                echo "<script> alert('用户添加成功!'); </script>";
                return view('/cms/index');
            }else {
                // 验证没通过就显示错误提示信息
                echo "<script>history.go(-1); alert('用户名或邮箱已存在!');</script>";
            }
        }
        else{
            return Redirect::intended('/cms/login.php');
        }
    }
    //用户信息修改页
    public function user_info(){
        session_start();
        if(isset($_SESSION['username'])){
            return view('/cms/user_info');
        }
        else{
            return Redirect::intended('/cms/login.php');
        }
    }
    //修改用户信息
    public function updateUser(){
        session_start();
        if(isset($_SESSION['username'])){
            if (Auth::attempt(array('name'=>$_SESSION['username'], 'password'=>Input::get('currentPassword')))){
                $password = Input::get('password');
                $passwordConfirm = Input::get('passwordConfirm');
                if(strlen($password) < 8){
                    echo "<script>history.go(-1); alert('密码至少8位!');</script>";
                    return;
                }
                if($password == $passwordConfirm){
                    $password = Hash::make($password);
                }
                else{
                    echo "<script>history.go(-1); alert('请输入相同的密码!');</script>";
                    return;
                }
                $user = User::where('name',$_SESSION['username'])->first();
                $user->email = Input::get('email');
                $user->password = $password;

                $user->save();

                echo "<script> alert('信息修改成功!'); </script>";
                return view('/cms/user_info');
            }
            else{
                echo "<script>history.go(-1); alert('当前密码错误!');</script>";
                return;
            }
        }
        else{
            return Redirect::intended('/cms/login.php');
        }
    }
}