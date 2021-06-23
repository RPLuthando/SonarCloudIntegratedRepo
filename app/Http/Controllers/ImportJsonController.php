<?php

namespace App\Http\Controllers;

use App\Survey;
use App\User;
use App\Entity;
use App\Assocentity;
use Exception;
use DB;
use App\RoleUser;
use Redirect;
use Mail;
use Helper;
 
use Illuminate\Http\Request;

class ImportJsonController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        
        try {
            return view('admin.pages.dashboard.import_json.json_list');
        }catch (Exception $exception) {
            return $exception->getMessage();
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        try{  
            $getentites = Entity::where('is_deleted',0)->get(['id','name']);  
            $getsurvey = Survey::where('is_deleted',0)->get(['id','name']);     
              
            return view('admin.pages.dashboard.import_json.addnewjson', compact('getentites', 'getsurvey'));         
           
          }catch(Exception $ex){
            $this->commonLog($ex);
            return Redirect::back()->with('error_message',config('messages.msgs.wrong_user'));
          }
    }
}
