<?php

/**
 * User: Warlof Tutsimo <loic.leuilliot@gmail.com>
 * Date: 29/12/2017
 * Time: 19:51.
 */

namespace Jambo\Seat\QQ\Commands;

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
        // 获取 mum 用户列表
        $mumble_users = DB::table('mumble_mumbleuser')->pluck('user_id');
        foreach ($mumble_users as $user_id) {

            // 获取用户所属组
            $squads_list = DB::table('squad_member')->where('user_id', $user_id)->pluck('squad_id')->toArray();

            // 如果组为空，则删除 mum 用户, 并进入下一轮
            if (empty($squads_list)) {
                DB::table('mumble_mumbleuser')->where('user_id', $user_id)->delete();
                continue;
            }

            // 获取用户groups列表，以 ',' 连接
            $groups_str = '';
            foreach ($squads_list as $squad_id) {
                $squad_name = DB::table('squads')->where('id', $squad_id)->value('name');
                if ($groups_str == '') {
                    $groups_str = $squad_name;
                } else {
                    $groups_str = $groups_str . ',' . $squad_name;
                }
            }

            // 获取主角色名
            $char_name = DB::table('users')->where('id', $user_id)->value('name');
            // 获取主角色 ID
            $char_id = DB::table('character_infos')->where('name', $char_name)->value('character_id');
            $corp_history_list = DB::table('character_corporation_histories')
                ->where('character_id', $char_id)->pluck('corporation_id')->toarray();
            // 获取主角色军团 ID
            $corp_id = end($corp_history_list);
            // 获取军团简称
            $corp_ticker = DB::table('corporation_infos')->where('corporation_id', $corp_id)->value('ticker');

            // 获取用户 title
            $title = DB::table('titles')->where('user_id', $user_id)->value('title');

            // 如果没有title
            if ($title == null) {
                $display_name = sprintf('%s-%s', $corp_ticker, $char_name);
            } else {
                $display_name = sprintf('%s-%s/%s', $corp_ticker, $title, $char_name);
            }

            $mumble_user_info = ['groups' => $groups_str, 'display_name' => $display_name];
            DB::table('mumble_mumbleuser')->where('user_id', $user_id)->update($mumble_user_info);
        }


        $this->line('Mumble users check finish');
    }
}
