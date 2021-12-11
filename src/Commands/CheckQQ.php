<?php

/**
 * User: Warlof Tutsimo <loic.leuilliot@gmail.com>
 * Date: 29/12/2017
 * Time: 19:51.
 */

namespace Jambo\Seat\QQ\Commands;

use Seat\Web\Models\Squads\SquadMember;
use Jambo\Seat\QQ\Models\QQInfo;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

/**
 * Class Synchronise.
 *
 * @package Jambo\Seat\QQ\Commands
 */

class CheckQQ extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */

    protected $signature = 'QQ:check';

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
        //  Array ( [user_id ] => squad_id  )
        $SquadMembers = SquadMember::pluck('squad_id', 'user_id');

        // 获取表：squads 中 column: id, name 数据
        // Array ( [squad_id] => name ) 
        $SquadName = DB::table('squads')->pluck('name', 'id');

        // 获取表：qq 中 column：user_id 数据
        // Array ( [index] => user_id )
        $QQ = QQInfo::pluck('user_id');

        foreach ($QQ as $user_id) {
            // 如果 qq user 在 squad 中
            if (isset($SquadMembers[$user_id])) {
                // 修改 group 数据为当前 squad name
                QQInfo::where('user_id', $user_id)
                    ->update(['group' => $SquadName[$SquadMembers[$user_id]]]);
            } else {
                // 删除 qq user
                QQInfo::where('user_id', $user_id)->delete();
            }
        }
        $this->line('QQ check finish');
    }
}
