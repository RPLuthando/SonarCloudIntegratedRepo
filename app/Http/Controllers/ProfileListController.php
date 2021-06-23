<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Entity;
use App\Assocentity;
use DB;
use App\RoleUser;
use Exception;
use Redirect;
use Mail;
use Helper;
use App\Http\Requests\AddNewUserlist as AddNewUserlist;


class ProfileListController extends Controller
{
    // role based categorisation of user 
    public function usersinfo($type=null) {
       
       
        if(!empty($type)){ 
            if($type=="super_user"){
               
                $users = fetch_users_via_role_id(1);
            }
            if($type=="management_user"){
                $users = fetch_users_via_role_id(2);
            }
            if($type=="responsible_user"){
                $users = fetch_users_via_role_id(3);
            }  
            if($type=="all_users"){
                $users = fetch_users_via_role_id(0);
            }       
        }  
        return view('admin.pages.dashboard.user.user_information', compact('users'));
    }

    //delete user recoverd using admin///
    public function recover($id){ 
        $user = User::withTrashed()->where('id',$id)->first(); 
        $user->restore();
        return Redirect::back()->with('flash_message','User Recovered!'); 
    }
    //edit form of user having two details/////
    public function edituserinfo(Request $request, $id)
    {
        $role_user = RoleUser::select('role_id')->where('user_id',$id)->get()->toArray();
        $role_user = array_column($role_user, 'role_id');
        $data = Assocentity::select('entity_id','group')->where('user_id',$id)->get()->toArray();
        $dataColumn = array_column($data, 'entity_id');
        $userDetails = User::select('name','email')->find($id); 
        $entityDetails = Entity::where('is_deleted',0)->pluck('name', 'id'); 
        //     
        return view('admin.pages.dashboard.user.edit_user_information', compact('id','userDetails','entityDetails', 'data', 'dataColumn','role_user'));
    }
    //edit update form of user /////
    public function editforminfo(Request $request){
        try
        {
            
            $userEdit = User::findOrFail($request->edit_user_id);            
            $userEdit->name = $request->new_user_name;
            $userEdit->email = $request->new_user_email; 
             
            if($request->user_type==3){
            $asscoentity = new Assocentity;
            $user_id = $request->edit_user_id;
            $date =  date('Y-m-d H:i:s');          
            $entity = $request->input('entity_name');
            $group = $request->input('groupName');
            $res=Assocentity::where('user_id',$user_id)->get();
            if($res){
                Assocentity::where('user_id',$user_id)->delete();
            }                                  
            foreach($entity as $key) {              
                $data = array('user_id' => $user_id, 'entity_id' => $key, 'group' => $group, 'created_at' => $date, 'updated_at' => $date); 
                Assocentity::insert($data);
            }
        }
            $userEdit->update();
            return redirect('/userlist/all_users')->with('flash_message',config('messages.msgs.details_update'));
        } catch(Exception $ex){
            $this->commonLog($ex);
            return Redirect::back()->with('error_message',config('messages.msgs.wrong_user'));
        }
    }

    // soft delete user /////
    public function softDeleted($id)  
    {
        $user = User::findOrFail($id); 
        $user->delete();
        return Redirect::back()->with('flash_message','User Deleted!');
    }

    //redirect to add details of user////
    public function adduserinfo($ut){ 
        try{           
            $entity_type = Entity::where('is_deleted',0)->get(['id','name']);           
            $user_type = $ut;
            return view('admin.pages.dashboard.user.addnewuser',compact('user_type', 'entity_type'));
        }catch(Exception $ex){
            $this->commonLog($ex);
            return Redirect::back()->with('error_message',config('messages.msgs.wrong_user'));
        }
    }

    //update new user values////BOM
    public function addinfo(AddNewUserlist $request){
        try{
            $userinfo = new User;
            $userinfo->name = $request->new_user_name;
            $userinfo->email = $request->new_user_email;
            $userinfo->save();
             
            $asscoentity = new Assocentity;
            $user_id = $userinfo->id;
            $date =  date('Y-m-d H:i:s');          
            $entity = $request->input('entity_name');           
            $group = $request->groupName;

            $assignrole = new RoleUser;
            $assignrole->user_id = $userinfo->id;
            if($request->user_type=="super_user"){
                $assignrole->role_id = 1;
            }
            if($request->user_type=="management_user"){
                $assignrole->role_id = 2;
            }
            if($request->user_type=="responsible_user"){
                $assignrole->role_id = 3;
                foreach($entity as $key) {
                    $data = array('user_id' => $user_id, 'entity_id' => $key, 'group' => $group, 'created_at' => $date);               
                   Assocentity::insert($data); 
                }
                // Mail::send('emails.new_user_details', ['userinfo' => $userinfo], function ($m) use ($userinfo) {                    
                //     $m->to($userinfo->email, $userinfo->name)->subject('PayU invites you to use Rapid Assurance for MSS Surveys');
                // });
            }
            
            $assignrole->save();
            
            return redirect('/userlist/'.$request->user_type)->with('flash_message','User Created!');
        }catch(Exception $ex){
            //dd($ex);
                $this->commonLog($ex);
                return Redirect::back()->with('error_message',config('messages.msgs.wrong_user'));
        }
    }
}
