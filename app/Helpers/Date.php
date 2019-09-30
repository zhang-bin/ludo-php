<?php

namespace App\Helpers;


class Date
{
    public static function lastMonth()
    {
        $thisMonth = strtotime(date('F 1'));
        return date('Y-m', strtotime('-1 month', $thisMonth));
    }

    public function thisSaturday()
    {
        $dayNumber = intval(date('N'));
        if ($dayNumber == 7) {//星期天
            $saturday = strtotime('last saturday');
        } else {
            $saturday = strtotime('saturday this week');
        }
        return $saturday;
    }

    public function thisFriday()
    {
        $dayNumber = intval(date('N'));
        if ($dayNumber == 7) {//星期天
            $friday = strtotime('last friday');
        } else {
            $friday = strtotime('friday this week');
        }
        return $friday;
    }

    public function thisMonday()
    {
        $dayNumber = intval(date('N'));
        if ($dayNumber == 7) {//星期天
            $monday = strtotime('last monday');
        } else {
            $monday = strtotime('monday this week');
        }
        return $monday;
    }

    public function thisSunday()
    {
        return strtotime('sunday this week');
    }
}