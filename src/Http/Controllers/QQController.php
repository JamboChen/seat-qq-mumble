<?php
/*
This file is part of SeAT

Copyright (C) 2015 to 2020  Leon Jacobs

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License along
with this program; if not, write to the Free Software Foundation, Inc.,
51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.
*/

namespace Jambo\Seat\QQ\Http\Controllers;

use Seat\Web\Http\Controllers\Controller;
use Jambo\Seat\QQ\Models\QQInfo;
use Jambo\Seat\QQ\Models\Titles;

/**
 * Class HomeController.
 *
 * @package Jambo\Seat\YourPackage\Http\Controllers
 */
class QQController extends Controller
{
    /**
     * @return \Illuminate\View\View
     */
    public function getHome()
    {
        $qqinfo = QQInfo::where('user_id', $userInfo = auth()->user()->id)->value('qq');
        $title = Titles::where('user_id', $userInfo = auth()->user()->id)->value('title');

        return view('yourpackage::qq', compact('qqinfo', 'title'));
    }

    public function setQQ()
    {

        //获取主角色信息
        $userInfo = auth()->user();


        Titles::updateOrInsert(
            ['user_id' => $userInfo->id],
            ['title' => request('title')]
        );

        $qq = request('qq');

        //查询 QQ 是否已被绑定
        $qqCheck = QQinfo::where('qq', $qq)->where('user_id', '<>', $userInfo->id)->first();

        // 没有则写入
        if ($qqCheck == null) {
            QQInfo::updateOrInsert(
                ['user_id' => $userInfo->id],
                ['qq' => $qq, 'char_name' => $userInfo->name, 'title' => request('title')]
            );
            return redirect()->back()->with('success', '设置成功');
        } else {
            return redirect()->back()->with('error', 'QQ已被绑定');
        }
    }
}
