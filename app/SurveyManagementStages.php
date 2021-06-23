<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\QuestionCat;
use App\User;

class SurveyManagementStages extends Model
{
    protected $table="survey_management_stages";
      	/**
    * The database primary key value.
    *
    * @var string
    */
    protected $primaryKey = 'id';
   	protected $fillable = ['user_id','survey_id','stage_first'];

 	public function userList(){
 	 	return $this->belongsTo('\App\User','user_id');
 	}
 	public function surveyDetails(){
         return $this->belongsTo(QuestionCat::class, 'id')->select('id','question_cat_name', 'survey_type', 'issued_date', 'target_date', 'survey_owner_id');
    }
}	
