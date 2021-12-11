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
use Jambo\Seat\QQ\Models\Titles;
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
        $this->line('Mumble users check start');
        // 获取表：title 中 column: title, user_id 数据
        //Array ( [user_id] => title ) 
        $titles = Titles::pluck('title', 'user_id');

        // 获取表：squad_member 中 column: squad_id, user_id 数据
        //  Array ( [user_id ] => squad_id  )
        $SquadMembers = SquadMember::pluck('squad_id', 'user_id');

        // 获取表：squads 中 column: id, name 数据
        // Array ( [squad_id] => name ) 
        $SquadName = DB::table('squads')->pluck('name', 'id');

        // 获取表：mumble_mumbleuser 中 column：user_id 数据
        // Array ( [index] => user_id )
        $Mumberusers = Mumble::pluck('user_id');
        $this->line('Mumble users check start');
        foreach ($Mumberusers as $user_id) {
            // 如果 mumble user 在 squad 中
            if (isset($SquadMembers[$user_id])) {
                // 修改 groups 数据为当前 squad name
                Mumble::where('user_id', $user_id)
                    ->update([
                        'groups' => $SquadName[$SquadMembers[$user_id]]
                    ]);

                // 获取角色名字
                $char_name = DB::table('users')->where('id', $user_id)->value('name');

                // 获取公司简称
                $char_id = DB::table('character_infos')->where('name', $char_name)->value('character_id');
                $corp_ids = DB::table('character_corporation_histories')->where('character_id', $char_id)->pluck('corporation_id');
                $corp_id = $corp_ids[sizeof($corp_ids) - 1];
                $corp_ticker = DB::table('corporation_infos')->where('corporation_id', $corp_id)->value('ticker');

                // 如果有title
                if (isset($titles[$user_id])) {
                    $display_name = sprintf("%s-%s/%s", $corp_ticker, $titles[$user_id], $char_name);
                } else {
                    $display_name = sprintf("%s-%s", $corp_ticker, $char_name);
                }

                Mumble::where('user_id', $user_id)
                    ->update([
                        'display_name' => $display_name
                    ]);
            } else {
                // 删除 mumble user
                Mumble::where('user_id', $user_id)->delete();
            }
        }
        $this->line('Mumble users check finish');
    }
}
