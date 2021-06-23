<?php

namespace App\Http\Controllers; 

use App\Survey;
use Exception;
use DB;
use App\RoleUser;
use Illuminate\Http\UploadedFile;
use Redirect;
use Mail;
use Helper;
use Illuminate\Http\Request;
use App\Tools\UploadTrait;
use Illuminate\Support\Facades\Storage;
class SurveyController extends Controller
{
    use UploadTrait;
	/*  @param
	    @description: get survey data from survey table
	    @return: sqlQuery variable as an data object
     */
	
	public function getSurveyList()
	{
		try {
		$surveyData = Survey::where('is_deleted', 0)->get();		
		return view('admin.survey.surveyList1',['getSurvey'=>$surveyData]);
		}catch (Exception $exception) {
			return $exception->getMessage();
		}
    }
    /*  @param
	    @description: update survey deadline from survey table
	    @return: sqlQuery variable as an data object
     */
	public function updateSurveyDeadLine(Request $request)
	{
        try {
			Survey::where('id',$request->survey_id)->update(['deadline'=>$request->deadline]);
			return response(['success'=>true,'message'=>'Data updated successfully!!']);			
			}catch (Exception $exception) {
				return $exception->getMessage(); 
			}  
    }

    public function saveGroup(Request $request)
	{
        $groupname =  $request['postGroup'];
        $survey_id = $request['sid'];
        
        Survey::where('id', $survey_id)->update(['group'=>$groupname]);
        return Redirect::back()->with('flash_message','Group updated successfully!!!');
    }

    public function getGroup(Request $request)
	{
        $survey_id = $request['sid'];
        return Survey::where('id', $survey_id)->get();
    }
    

    /*  @param
	    @description: get survey object data from survey table
	    @return: sqlQuery variable as an data object
     */
    private function getObjectFromStorage()
    {
		try {
	        return Survey::where('is_deleted', 0)->get();
        } catch (Exception $exception) {
            return $exception->getMessage();
        }
    }

    /*  @param $accessKey
	    @description:get values from above function of surveys list
	    @ return $json;
    */
    public function getSurveys($id = null)
    {
    	try {
            if($id){
                $result = Survey::where('id',$id)->first(); 
                return $result;  
            }else{
                $result = Survey::where('is_deleted', 0)->get();
                return json_encode($result);
            }

        } catch (Exception $exception) {
            return $exception->getMessage();
        }
    }

    /*  @param $id
	    @description:get values from  surveys list
	    @ return $surveyJson;
    */
    public function getSurvey(Request $request)
    {
    	try{
			$storage = $this->getSurveys($request->surveyId);			
			if(!empty($storage)){
				return $storage->getAttributes()["json"];
			}	        
    	} catch (Exception $exception) {
            return $exception->getMessage();
        }
    }

    /*  @param $accessKey and $name
	    @description: update values in surveys list
	    @ return $json;
    */
    public function addSurvey($accessKey = null, $name)
    {
    	try{
	    	$surveyData = new Survey();
			$surveyData->name = $name;
            $surveyData->deadline = date ("Y-m-d", strtotime (date("Y-m-d") ."+10 days"));
            $surveyData->created_by = auth()->user()->id;
			$surveyData->save();
			$survey = array('Name' => $name, 'Id' => $surveyData->id); 
	        $json = json_encode($survey);
			return $json;			
        } catch (Exception $exception) {
            return $exception->getMessage(); 
        }
    }
    /*  @param 
	    @description: edit and create survey 
	    @ return $json;
    */
    public function editorSurvey(Request $request){
        
        $id = (int) $request->id;

        $data = $this->getSurveys($request->id);
        $deadline = $data->getAttributes()["deadline"];
        $name = $data->getAttributes()["name"];
        $group = $data->getAttributes()["group"];       
    	return view('admin.pages.dashboard.superuser_surveydata.surveyeditor', compact('id','name','deadline','group')); 
    }

    /*  @param 
	    @description: Change survey name  
	    @ return $json;
    */
    public function changeSurveyName(Request $request)
    {
        try{
           return Survey::where('id',$request->id)->update(['name' => $request->name]);
        } catch (Exception $exception) {
           return $exception->getMessage();
        }
    }

    /*  @param 
	    @description: Get survey deadline date 
	    @ return $json;
    */
    public function expdateSurvey(Request $request)
    {
        try{
           return Survey::where('id',$request->id)->update(['deadline' => $request->date]);
        } catch (Exception $exception) {
           return $exception->getMessage();
        }
    }
    

      public function changeJson(Request $request) {       	
	    try{			
			return Survey::where('id',$request->Id)->update(['json' => $request->Json]);		   
        } catch (Exception $exception) {
            return $exception->getMessage();
        }
	  }
      
     /*  @param $id
	    @description: Delete live survey or survey  
	    @ return ;
    */ 
	public function deleteSurveylive($id) 
    {
        try{			
		   Survey::where('id',$id)->update(['is_deleted' => 1]);
		   return Redirect::back()->with('flash_message','Survey Recovered!');
        } catch (Exception $exception) {
           return $exception->getMessage();  
        }
    }
    /*  @param $id
	    @description: Delete live survey or survey  
	    @ return $json;
    */
    public function deleteSurvey($id)
    {
        try{
           return Survey::where('id',$id)->update(['is_deleted' => 1]);
        } catch (Exception $exception) {
           return $exception->getMessage();
        }
    }
    /*  @param 
	    @description: Run survey for responsible user  
	    @ return $json;
    */
    public function surveyRun(Request $request)  
    {    
        
        $surveyData = Survey::where('id',$request->id)->first();  
        //dd($surveyData);      
        return view('admin.pages.dashboard.responsibleuser_surveydata.surveyrunnew', compact($request->id,'surveyData')); 
    }

    /*  @param 
	    @description: for save survey results 
	    @ return $json;
    */


    public function saveResult(Request $request)
    {
       try{
            $values = ['survey_id' =>$request->postId,'json' => $request->surveyResult];
            DB::table('survey_results')->insert($values);
        }catch(Exception $exception){
            return $exception->getMessage();
        }
    }

    public function saveFile(Request $request)
    {
        //return $request->files->store('evidence');
        //dd($request->files);
        /*foreach ($request->files as $files)  {
            
            if ($files instanceof UploadedFile) {*/
                //$postdata = $request->files;
                //$myfile = time().str_random();
                //Storage::disk('local')->put($postdata);
                //return $request->files->store('public/evidence');
                //return $request->files->store('files', ['disk' => 'public/evidence']);
            /*}
            else {
                return 'No File';
            }
        }


        return response()->json(['success'=>'Ajax request submitted successfully']);*/
    }

}
