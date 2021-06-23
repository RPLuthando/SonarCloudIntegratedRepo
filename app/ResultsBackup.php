<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ResultsBackup extends Model
{
    protected $table = "results_backup";
    protected $fillable = ['survey_id',
    'json',         
    'custom_json',
    'review_json',
    'review_custom_json',
    'partial_data',
    'total_score',
    'inital_score',
    'review_score',
    'manage_score',
    'current_score',
    'change_count',
    'res_uid',
    'status',
    'entity_id'];
}
