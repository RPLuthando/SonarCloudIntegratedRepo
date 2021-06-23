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
use App\Results;
use Auth;
use Illuminate\Http\Request;
 
class EntityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        try {
            
            $getentites = Entity::where('is_deleted',0)->get(['id','name']);  
            $id = $getentites[0]['id'];
            //dd($id);
            //$revision_nr = Results::select('survey_id','revision_number')->where('entity_id', $id)->get();
            //$revision_nr = Results::where('is_deleted',0)->get(['id','revision_nr']); 
            //dd($revision_nr);
            return view('admin.pages.dashboard.entity.entity_list', compact('getentites','revision_nr'));
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
            return view('admin.pages.dashboard.entity.addnewentity');
          }catch(Exception $ex){
            $this->commonLog($ex);
            return Redirect::back()->with('error_message',config('messages.msgs.wrong_user'));
          }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      
        try{
           
            $createEntity = Entity::create([
                'name' => $request->name,                 
            ]);     
            return redirect('/entitylist')->with('flash_message','Entity Created!');
        }catch(Exception $ex){
            $this->commonLog($ex);
            return Redirect::back()->with('error_message',config('messages.msgs.wrong_user'));
        }
    }
    

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
       
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    { 
        try{   
            $revision_nr = Results::select('survey_id','revision_number','status')->where('entity_id', $id)->get();
            $survey_name = Survey::select('id','name','group')->WhereNotNull('group')->orderBy('group', 'ASC')->get();
            $entityDetails = Entity::select('name')->find($id);  
            
            //dd($entity_data);
            $current_revision_array = [];
            
            foreach ($survey_name as $key_sur => $value_sur) {
                $current_revision['survey_id'] = $value_sur->id;
                $current_revision['revision_number'] = 'Survey in initial stage';
                $current_revision['name'] = $value_sur->name;
                $current_revision['group'] = $value_sur->group;
                $current_revision['selected'] = false;
                $current_revision['display'] = false;
                foreach ($revision_nr as $key_rev => $value_rev) {
                     if($value_sur->id === $value_rev->survey_id && $value_rev->revision_number !== null) {
                         $current_revision['survey_id'] = $value_sur->id;
                         $current_revision['revision_number'] = $value_rev->revision_number;
                         $current_revision['name'] = $value_sur->name;
                         if (isset($value_rev->status) && $value_rev->status === 'Complete_initial' || $value_rev->status === 'Review_in_progress') {
                            $current_revision['selected'] = true;
                         } else {
                            $current_revision['selected'] = false;
                         }
                         
                         $current_revision['display'] = true;
                     } elseif($value_sur->id === $value_rev->survey_id && $value_rev->revision_number === null) {
                         $current_revision['survey_id'] = $value_sur->id;
                         $current_revision['revision_number'] = 0;
                         $current_revision['name'] = $value_sur->name;
                         if (isset($value_rev->status) && $value_rev->status === 'Complete_initial' || $value_rev->status === 'Review_in_progress') {
                            $current_revision['selected'] = true;
                         } else {
                            $current_revision['selected'] = false;
                         }
                         $current_revision['display'] = true;
                     }   
                 }
                array_push($current_revision_array,$current_revision);
             }

             //dd($current_revision_array);
             //echo 'Group: ' . $entity_data[0]->group;
           
            
            return view('admin.pages.dashboard.entity.editentity', compact('id','entityDetails','current_revision_array','entity_data'));
          }catch(Exception $ex){
            $this->commonLog($ex);
            return Redirect::back()->with('error_message',config('messages.msgs.wrong_user'));
          }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) 
    {
        try
        {
            $entityEdit = Entity::findOrFail($id);
            $entityEdit->name = $request->edit_entity_name;           
            $entityEdit->update();
            return redirect('/entitylist')->with('flash_message',config('messages.msgs.details_update'));
        } catch(Exception $ex){
            $this->commonLog($ex);
            return Redirect::back()->with('error_message',config('messages.msgs.wrong_user'));
        }  
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
  
    // soft delete entity//
    public function deleteEntity($id) 
    {
        try{			
            Entity::where('id',$id)->update(['is_deleted' => 1]);
            Assocentity::where('entity_id',$id)->update(['is_deleted' => 1]);       
		   return Redirect::back()->with('flash_message','Entity Deleted Successfully!');
        } catch (Exception $exception) {
           return $exception->getMessage();
        }
    }
     
}
