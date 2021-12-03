<?php
/**
 * User: Warlof Tutsimo <loic.leuilliot@gmail.com>
 * Date: 01/12/2017
 * Time: 20:42.
 */

namespace Jambo\Seat\QQ\Models;

use Illuminate\Database\Eloquent\Model;
use Seat\Eveapi\Models\Sde\InvGroup;

class QQInfo extends Model
{

    public $timestamps = true;

    protected $primaryKey = 'id';

    protected $table = 'qq';

    protected $fillable = [
        'qq','char_id'
    ];
}