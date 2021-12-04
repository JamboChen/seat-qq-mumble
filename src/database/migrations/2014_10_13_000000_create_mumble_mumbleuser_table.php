<?php

/*
 * This file is part of SeAT
 *
 * Copyright (C) 2015 to 2021 Leon Jacobs
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateMumbleMumbleuserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(Schema::hasTable('mumble_mumbleuser')) return; 
        Schema::create('mumble_mumbleuser', function (Blueprint $table) {

            $table->unsignedInteger('user_id')->primary();
            
            $table->string('username', 254);
            $table->string('pwhash', 90);
            $table->unsignedInteger('groups')->nullable();
            $table->string('hashfn', 20);
            $table->string('display_name', 254);
            $table->string('certhash', 254)->nullable();
            $table->dateTime('last_connect', 6)->nullable();
            $table->dateTime('last_disconnect', 6)->nullable();
            $table->longText('release')->nullable();
            $table->INTEGER('version')->nullable();
        });

        Schema::table('mumble_mumbleuser', function(Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users');
        });
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        Schema::drop('mumble_mumbleuser');
    }
}
