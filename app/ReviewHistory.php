<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReviewHistory extends Model
{
    protected $table = "review_histories";
    /**
    * The database primary key value.
    *
    * @var string
    */
    protected $primaryKey = 'id';
    protected $fillable = ['user_id', 'response_date'];
}
