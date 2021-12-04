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
use Jambo\Seat\QQ\Models\Mumble;
use Seat\Eveapi\Models\Character\CharacterInfo;
use Seat\Eveapi\Models\Corporation\CorporationMember;
use Illuminate\Support\Facades\Hash;
use Seat\Web\Models\Squads\SquadMember;
/**
 * Class HomeController.
 *
 * @package Jambo\Seat\YourPackage\Http\Controllers
 */
class MumbleController extends Controller
{
    /**
     * @return \Illuminate\View\View
     */
    public function getHome()
    {
        $user = auth()->user();

        $mumDB = Mumble::where('user_id', '=', $user->id)->first();
        $address = '27.50.162.226';
        $port = '64738';
        $username = $user->main_character_id;

        $SquadMembers = SquadMember::select('squad_id','user_id')->get()->toArray();
        $test = gettype($SquadMembers[0]['squad_id']);

        return view('yourpackage::mumble', compact('address', 'port', 'username', 'test'));
    }

    public function setpw()
    {

        // 获取主角色ID
        $user = auth()->user();

        //查询是否有数据
        $muminfo = Mumble::where('user_id', '=', $user->id)->first();

        //没有数据则增加
        if ($muminfo == null) {
            $mum_info = new Mumble();
            $mum_info->user_id = $user->id;
            $mum_info->username = $user->main_character_id;
            $mum_info->pwhash = Hash::make(request('setpw'));
            $mum_info->hashfn = 'bcrypt';
            $mum_info->display_name = $user['name'];
            $mum_info->save();
            
        }
        //有数据则修改
        else {
            //$muminfo->pwhash = Hash::make(request('setpw'),['rounds'=>12]);
            $muminfo->pwhash = password_hash(request('setpw'), PASSWORD_DEFAULT);
            $muminfo->save();
        }
        return redirect()->back()->with('success', '设定成功');
    }
}
