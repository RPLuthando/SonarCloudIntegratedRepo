<?php

namespace App;
use App\Questionoptions;
use App\QuestionCat;
use Illuminate\Database\Eloquent\Model;

class SurveyQuestion extends Model
{
    
    protected $fillable = [
        'question_name','question_cat_id','question_status','user_id'
    ];
    protected $table = 'survey_questions';
    /**
    * The database primary key value.
    *
    * @var string
    */
    protected $primaryKey = 'id';
     public function questionoption()
        {
            return $this->hasMany('\App\Questionoptions','question_id','id');      
        }
     public function category(){
     	return $this->hasMany('\App\QuestionCat','question_cat_id','id');
     }

     public function response(){
        return $this->hasMany('\App\SurveyResponse','survey_id','user_id');
     }
    public function questionTitles() {
        return $this->hasMany('\App\Questionoptions','question_id','id')->select(['question_id','score_current','ideal_standard','question_options']);
    }
    public function questionReports() {
        return $this->hasMany('\App\Questionoptions','question_id','id')->select(['question_id','score_current','ideal_standard','question_options'])->where('ideal_standard', 'Yes');
    }

     public function responseGet(){
        return $this->belongsTo('\App\SurveyResponse','id');
     }
     
}
