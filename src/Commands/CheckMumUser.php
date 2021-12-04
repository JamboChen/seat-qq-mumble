<?php

/**
 * User: Warlof Tutsimo <loic.leuilliot@gmail.com>
 * Date: 29/12/2017
 * Time: 19:51.
 */

namespace Jambo\Seat\QQ\Commands;

use Seat\Web\Models\Squads\SquadMember;
use Jambo\Seat\QQ\Models\Mumble;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

/**
 * Class Synchronise.
 *
 * @package Jambo\Seat\QQ\Commands
 */

class CheckMumUser extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */

    protected $signature = 'mumble:check:user';

    /**
     * The console command description.
     *
     * @var string
     */

    protected $description = '检查过期角色';

    /**
     * Execute the console command.
     */

    public function handle()
    {
        // 获取表：squad_member 中 column: squad_id, user_id 数据
        // Array([0] => Array ( [squad_id] => squad_id [user_id] => user_id ))
        $SquadMembers = SquadMember::select('squad_id', 'user_id')->get()->toArray();

        // 转成以下格式
        // Array ( user_id => squad_id )
        $members = array();
        foreach ($SquadMembers as $i) {
            $members[$i['user_id']] = $i['squad_id'];
        }


        // 获取表：squads 中 column: id, name 数据
        // Array ( [0] => stdClass Object ( [id] => 1 [name] => member ) )
        $SquadName = DB::table('squads')->select('id', 'name')->get()->toArray();

        // Array ( id => name )
        $squadName = array();
        foreach ($SquadName as $i) {
            $squadName[$i->id] = $i->name;
        }


        // 获取表：mumble_mumbleuser 中 column：user_id 数据
        // Array ( [0] => Array ( [user_id] => user_id )
        $Mumberusers = Mumble::select('user_id')->get()->toArray();

        foreach ($Mumberusers as $i) {
            // 如果 mumble user 在 squad 中
            $user_id = $i['user_id'];
            if (array_key_exists($user_id, $members)) {
                // 修改 group 数据为当前 squad name
                $userInfo = Mumble::where('user_id', $user_id)->first();
                $userInfo->groups = $squadName[$members[$user_id]];
                $userInfo->save();
            } else {
                // 删除 mumble user
                Mumble::where('user_id', $user_id)->delete();
            }
        }
    }
}
