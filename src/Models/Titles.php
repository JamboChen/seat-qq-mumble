<?php

/**
 * User: Warlof Tutsimo <loic.leuilliot@gmail.com>
 * Date: 01/12/2017
 * Time: 20:42.
 */

namespace Jambo\Seat\QQ\Models;

use Illuminate\Database\Eloquent\Model;

class Titles extends Model
{
    protected $table = 'titles';

    protected $primaryKey = 'user_id';

    public $timestamps = false;

    protected $fillable = [
        'title', 
    ];
}
