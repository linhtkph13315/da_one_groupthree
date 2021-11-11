<?php

namespace app;

use database\DB;

class Users
{
    public function nameSeparation($name) : array
    {
        $name_arr = explode(" ", $name);
        $first_name = count($name_arr) > 1 ? array_pop($name_arr) : $name;
        $last_name = count($name_arr) > 1 ? implode(" ", $name_arr) : '';
        return [
            'first_name' => $first_name,
            'last_name' => $last_name
        ];
    }

    public function findUserByID($user_id)
    {
        return DB::table('users')->select('*')
            ->where('user_id', '=', $user_id)
            ->execute()->first();
    }

    public function findEmail($email)
    {
        return DB::table('users')->select('*')
            ->where('email', '=' , $email)
            ->execute()->first();
    }
}