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
use Illuminate\Support\Facades\DB;
use Jambo\Seat\QQ\Models\QQInfo;
use Seat\Eveapi\Models\Character\CharacterInfo;
use Seat\Eveapi\Models\Corporation\CorporationMember;

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
        $char_id = auth()->user()->main_character["character_id"];
        $qqinfo = DB::table('qq')->where('char_id', $char_id)->value('qq');
        //$qqinfo = QQInfo::find(3);
        $request = "null";
        return view('yourpackage::myview', compact('qqinfo', 'request'));
    }

    public function setQQ()
    {
        
        //获取主角色ID
        $char_id = auth()->user()->main_character["character_id"];
        $test = auth()->user()->main_character;

        //查询是否有数据
        $qqinfo = QQinfo::where('char_id', '=', $char_id)->first();

        //没有数据则增加
        if ($qqinfo == null){
            $qqinfo = new QQInfo();
            $qqinfo->qq = request('qq');
            $qqinfo->char_id = $char_id;
            $qqinfo->save();
            return redirect()->back()->with('success', 'QQ绑定成功');
            
        }
        //有数据则修改
        else{
            if (QQinfo::where([
                    ['qq', '=', request('qq')],
                    ['char_id', '!=', $char_id]
                ])->first() == null){


                $qqinfo->qq = request('qq');
                $qqinfo->char_id = $char_id;
                $qqinfo->save();
                return redirect()->back()
                            ->with('success', 'QQ修改成功');
            }
            else{
                return redirect()->back()->with('error', 'QQ已被绑定');
                # return redirect()->back()->with('error', $test);
            }
        }
        
}
}
