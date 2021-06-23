<?php

namespace App;

use App\SurveyQuestion;
use App\SurveyResponse;
use Illuminate\Database\Eloquent\Model;

class Questionoptions extends Model
{
    protected $fillable = [
        'question_id', 'question_options', 'option_type',
    ];
    protected $table = 'question_options';

    public function questionoptions()
    {
        return $this->belongsTo('\App\SurveyQuestion', 'question_id', 'id');
    }

    public function questionList()
    {
        return $this->belongsTo('\App\SurveyQuestion', 'question_id', 'id');
    }

    //Review controller get questions from options
    public function questionsGet()
    {
        return $this->belongsTo(SurveyQuestion::class, 'question_id', 'id')->select('id', 'question_name');
    }
    
    public function responseDate()
    {
        return $this->belongsTo(SurveyResponse::class, 'id', 'option_id')->select('id', 'option_id', 'other', 'updated_at');
    }

}
