<?php

namespace App\Http\Controllers;

use App\Tag;
use App\Role;
use App\User;
use App\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    //
    public function index(Request $request){
        
        
        if(!Auth::check() && $request->path() !='login'){
            return redirect('/login');
        }
        
        if(!Auth::check() && $request->path() =='login'){
            return view('welcome');
        }

        $user = Auth::user();

        if($user->userType=='User'){
            return redirect('/login');
        }

        if($request->path() == 'login'){
            return redirect('/');
        }

        return $this->checkForPermission($user, $request);


    }
    public function checkForPermission($user ,$request){
       /*  $permission = json_decode($user->role->permission);
        echo $permission[0]->name; */

        //$permission = json_decode($user->role->permission);
        return view('welcome');
      $permission = json_decode($user->role->permission);

        $hasPermission = false;
        if(!$permission) return view('welcome');
        foreach($permission as $p){
            if($p->name == $request->path()){
                if($p->read){
                    $hasPermission = true;
                }
            }
        }

        if($hasPermission) return view('welcome');
        return view('notfound');


    }

    public function logout(){
        
        Auth::logout();
        return redirect('/login');
    }

    public function addTag(Request $request)
    {
        $this->validate($request, [
            'tagName' => 'required'
        ]);

        return Tag::create([
            'tagName' => $request->tagName
        ]);
    }
    //

    public function getTag(){
        return Tag::orderBy('id','desc')->get();
    }

    
    public function editTag(Request $request)
    {
        $this->validate($request, [
            'tagName' => 'required'
        ]);

        return Tag::where('id', $request->id)->update([
            'tagName' => $request->tagName
        ]);
    }
    
    

    public function deleteTag(Request $request)
    {
        return Tag::where('id', $request->id)->delete();
    }

    public function upload(Request $request)
    {  
        //return $picName = time().'.'.$request->file->extension(); 
        
        $this->validate($request, [
            'file' => 'required|mimes:jpeg,jpg,png'
        ]);


        $picName = time().'.'.$request->file->extension(); 
        $request->file->move(public_path('uploads'), $picName);
        return $picName;
           
       
    }

    public function deleteImage(Request $request)
    {
        $fileName = $request->imageName;

        $this->deleteFileFromServer($fileName, false);

        return 'done';
    }

    public function deleteFileFromServer($fileName, $hasFullPath = false){

        if(!$hasFullPath){
            $filePath = public_path().'/uploads/'.$fileName;
        }
        if(file_exists($filePath)){
            @unlink($filePath);
        }
        return ;
    }

    
    public function addCategory(Request $request)
    {
        $this->validate($request, [
            'categoryName' => 'required',
            'iconImage' => 'required'
        ]);

        return Category::create([
            'categoryName' => $request->categoryName,
            'iconImage' => $request->iconImage
        ]);
    }
    
    
    public function getCategory(){
        return Category::orderBy('id','desc')->get();
    }

    public function editCategory(Request $request)
    {
        $this->validate($request, [
            'categoryName' => 'required',
            'iconImage' => 'required'
        ]);

        return Category::where('id', $request->id)->update([
            'categoryName' => $request->categoryName,
            'iconImage' => $request->iconImage
        ]);
    }

    

    public function deleteCategory(Request $request)
    {
        $this->deleteFileFromServer($request->iconImage);
        return Category::where('id', $request->id)->delete();
    }

    
    public function createUser(Request $request)
    {
        $this->validate($request, [
            'fullName' => 'required',
            'email' => 'bail|required|email|unique:users',
            'password' => 'bail|required|min:6',
            'role_id' => 'required',
        ]);
        
        $password = bcrypt($request->password);

        $user = User::create([
            'fullName' => $request->fullName,
            'email' => $request->email,
            'password' => $password,
            'role_id' => $request->role_id,
        ]);

        return $user;
    }

    
    
    public function editUsers(Request $request)
    {
        $this->validate($request, [
            'fullName' => 'required',
           // 'email' => 'bail|required|email|unique:users',
            //이메일이 같은데 unique면 원래 한명이자나
            //근데 똑같은애가 와도 unique 되니까
            // 이 부분을 해결하기 위해서는
            
            'email' => "bail|required|email|unique:users,email,$request->id",
            //요론식으로 쓰는거임 그니까 $request->id는 1이고 만약에 수정하려는 id가 1이면 ok 뜰거고
            // 수정하려는 id가 2면 저건 error날거임
            'password' => 'min:6',
            'userType' => 'required',
        ]);
        

        $data = [
            'fullName' => $request->fullName,
            'email' => $request->email,
            'userType' => $request->userType,
        ];

        if($request->password){
            $password = bcrypt($request->password);
            $data['password'] = $password;
        }

        $user = User::where('id',$request->id)->update($data);
        return $user;
    }
    
    public function getUsers(){
        //return User::where('userType', '!=', 'User')->get();
        return User::get();
    }

    public function adminLogin(Request $request){
        
        $this->validate($request, [
            'email' => 'bail|required|email',
            'password' => 'bail|required|min:6',
        ]);

        if(Auth::attempt(['email'=>$request->email, 'password'=>$request->password ])){

            $user = Auth::user();

            if ($user->role->isAdmin==0){
                Auth::logout();
                return response()->json([
                    'msg' => 'incorrent login details',
                ], 401);    
            }

            return response()->json([
                'msg' => 'You ar logged in',
                'user' => $user,
            ]);

        }else{
            return response()->json([
                'msg' => 'incorrent login details',
            ], 401);
        }

    }

    
    public function addRole(Request $request)
    {
        $this->validate($request, [
            'roleName' => 'required',
          //  'permission' => 'required'
        ]);

        

        return Role::create([
            'roleName' => $request->roleName,
        ]);
    }

    
    public function getRole(){
        return Role::orderBy('id','desc')->get();
    }

    public function editRole(Request $request)
    {
        $this->validate($request, [
            'roleName' => 'required',
          //  'permission' => 'required'
        ]);

        

        $data = [
            'roleName' => $request->roleName,
        ];

        $role = Role::where('id',$request->id)->update($data);
        return $role;
    }


    public function assignRole(Request $request)
    {

        $this->validate($request, [
            'permission' => 'required',
            'id' => 'required',
        ]);

        return Role::where('id',$request->id)->update([
            'permission' => $request->permission,
        ]);
        
    }
}
