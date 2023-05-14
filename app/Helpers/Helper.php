<?php


namespace App\Helpers;


class Helper
{
    static function hasLoggedIn()
    {
        if (session()->has('id')) return true;
    }

    static function isAdmin()
    {
        if (session('roleId') == 1) return true;
    }

    static function isStudent()
    {
        if (session('roleId') == 2) return true;
    }

    static function isClassSupervisor()
    {
        if (session('roleId') == 3) return true;
    }

    static function isDeanFaculty()
    {
        if (session('roleId') == 4) return true;
    }

    static function isDeanSchool()
    {
        if (session('roleId') == 5 || session('roleId') == 6) return true;
    }

    static function isProfileSet()
    {
    }
}
