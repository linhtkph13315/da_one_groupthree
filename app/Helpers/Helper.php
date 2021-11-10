<?php

namespace app\Helpers;

use DateTime;

class Helper 
{
    public static function addTimeTo(string $date, int $number, string $unit = 'day') : string
    {
        return date('Y-m-d H:i:s', strtotime("+$number $unit", strtotime($date)));
    }

    public static function deleteTimeTo(string $date, int $number, string $unit = 'day') : string
    {
        return date('Y-m-d H:i:s', strtotime("-$number $unit", strtotime($date)));
    }

    public static function timeNow(string $date_add = '', string $unit = 'day') : string
    {
        if (!empty($date_add)) {
            return date('Y-m-d H:i:s', strtotime("+$date_add $unit"));
        }
        return date('Y-m-d H:i:s');
    }

    public static function priceVND($price) : string
    {
        return number_format($price, 0, '', '.')." ₫";
    }

    public static function calculatorTime(string $starttime) : string 
    {
        $end = new DateTime(date('Y-m-d H:i:s'));
        $start = new DateTime($starttime);
        $time = $end->diff($start);

        if ($time->y > 0)
        {
            return $time->y . " năm trước";
        }
        else if ($time->m > 0)
        {
            return $time->m . " tháng trước";
        }
        else if ($time->d > 0)
        {
            return $time->d . " ngày trước";
        }
        else if ($time->h > 0)
        {
            return $time->h . " giờ trước";
        }
        else if ($time->i > 0)
        {
            return $time->i . " phút trước";
        }
        return "Vừa xong";
    }

    public static function uploadImage($file = [], $folder = '')
    {
        $assets_path = ROOT_DIR . '/public/assets/img/';
        $file_path = $folder.'/' . date('Y') . '/' . date('m'). '/' . date('d');
        if (!is_dir($assets_path.$file_path))
        {
            mkdir($assets_path.$file_path, 0755, true);
        }

        $file_name = $file_path . '/' . time() . '_' . rand() . date('YmdHis') . '.' . pathinfo($file['name'], PATHINFO_EXTENSION);
        if (move_uploaded_file($file['tmp_name'], $assets_path.$file_name))
        {
            return $file_name;
        }
        return false;
    }
}