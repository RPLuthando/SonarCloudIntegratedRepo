<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;
use App\SurveyQuestion;
use App\SurveyManagementStages;
use App\RemidiationSurvey;
use App\SurveyResponse;
class QuestionCat extends Model
{

    protected $primaryKey = 'id';

    protected $fillable = [
        'question_cat_name','question_cat_status','survey_type','issued_date','target_date','survey_owner_id'
    ];
    protected $table = 'question_cats';
    public function user()
    {
        return $this->belongsTo('\App\User', 'survey_owner_id');      
    }
    public function data(){
    	return $this->belongsTo('\App\SurveyQuestion');
    }
    public function survey_status(){
        return $this->belongsTo('\App\SurveyManagementStages', 'id' );
    }
    public function surveyremediation(){
        return $this->belongsTo('\App\RemidiationSurvey', 'id')->select('id','survey_category_name','survey_type','issued_date','target_date','survey_owner_id','category_id');
    }
    public function questions(){
        return $this->hasMany('\App\SurveyQuestion',  'question_cat_id','id' );
    }
    public function stagesDetails(){
         return $this->belongsTo('\App\SurveyManagementStages', 'id', 'survey_id')->select('id','user_id','survey_id','stage_first')->where('user_id', auth()->user()->id );
    }
    public function users()
    {
        return $this->belongsTo('\App\User','survey_owner_id', 'id')->select('id','name');      
    }
}
