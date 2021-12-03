<?php
/**
 * User: Warlof Tutsimo <loic.leuilliot@gmail.com>
 * Date: 01/12/2017
 * Time: 20:42.
 */

namespace Jambo\Seat\QQ\Models;

use Illuminate\Database\Eloquent\Model;
use Seat\Eveapi\Models\Sde\InvGroup;

class Mumble extends Model
{
    protected $table = 'mumble_mumbleuser';

    protected $primaryKey = 'user_id';

    public $timestamps = false;

    protected $fillable = [
        'username','pwhash','hashfn','display_name','certhash'
    ];
}