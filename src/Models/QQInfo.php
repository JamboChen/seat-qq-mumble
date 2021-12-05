<?php

/**
 * User: Warlof Tutsimo <loic.leuilliot@gmail.com>
 * Date: 01/12/2017
 * Time: 20:42.
 */

namespace Jambo\Seat\QQ\Models;

use Illuminate\Database\Eloquent\Model;

class QQInfo extends Model
{
    protected $table = 'qq';

    protected $primaryKey = 'user_id';

    public $timestamps = false;

    protected $fillable = [
        'qq', 'char_name', 'group'
    ];
}
