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
        $user = auth()->user()->main_character;

        
        if (Mumble::where('username', '=', $user['character_id'])->first() == null){
            // $mum_info = new Mumble();
            // $mum_info->username = $user['character_id'];
            // $random = str_shuffle('abcdefghjklmnopqrstuvwxyzABCDEFGHJKLMNOPQRSTUVWXYZ234567890!$%^&!$%^&');
            // $password = substr($random, 0, 20);
            // $mum_info->pwhash = Hash::make($password);
            // $mum_info->hashfn = 'bcrypt';
            // $mum_info->display_name = $user['name'];
            // $mum_info.save();
        }

        $mumDB = Mumble::where('username', '=', $user['character_id'])->first();
        $address = '27.50.162.226';
        $port = '64738';
        $username = $user['character_id'];
        $random = str_shuffle('abcdefghjklmnopqrstuvwxyzABCDEFGHJKLMNOPQRSTUVWXYZ234567890!$%^&!$%^&');
        $password = substr($random, 0, 20);
        return view('yourpackage::mumble', compact('address', 'port', 'username', 'password'));
    }

    public function setpw()
    {

        // 获取主角色ID
        $user = auth()->user()->main_character;

        //查询是否有数据
        $muminfo = Mumble::where('username', '=', $user['character_id'])->first();

        //没有数据则增加
        if ($muminfo == null) {
            $mum_info = new Mumble();
            $mum_info->username = $user['character_id'];
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
