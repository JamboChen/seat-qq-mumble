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
        $mumble_users = DB::table('mumble_mumbleuser')->get();

        foreach ($mumble_users as $mumble_user) {
            $user_id = $mumble_user->user_id;

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

            // 获取用户信息 [id, 公司简称, title, 角色名]
            $users_info = DB::table('users')
                ->join('character_infos', 'character_infos.name', 'users.name')
                ->join('titles', 'titles.user_id', 'users.id')
                ->join('character_corporation_histories', 'character_corporation_histories.character_id', 'character_infos.character_id')
                ->join('corporation_infos', 'corporation_infos.corporation_id', 'character_corporation_histories.corporation_id')
                ->select('users.id', 'corporation_infos.ticker', 'titles.title', 'character_infos.name')
                ->where('users.id', $user_id)
                ->get()->toarray();

            $corp_ticker = $users_info[0]->ticker;
            $title = $users_info[0]->title;
            $char_name = $users_info[0]->name;

            // 如果没有title
            if ($title == null) {
                $display_name = sprintf('%s-%s', $corp_ticker, $char_name);
            } else {
                $display_name = sprintf('%s-%s/%s', $corp_ticker, $title, $char_name);
            }

            // 如果数据相同就跳过
            if ($mumble_user->groups == $groups_str and $mumble_user->display_name == $display_name) {
                continue;
            }
            $mumble_user_info = ['groups' => $groups_str, 'display_name' => $display_name];
            DB::table('mumble_mumbleuser')->where('user_id', $user_id)->update($mumble_user_info);
        }

        $this->line('Mumble users check finish');
    }
}
