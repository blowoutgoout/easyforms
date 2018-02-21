<?php
/**
 * Copyright (C) Baluart.COM - All Rights Reserved
 *
 * @since 1.0
 * @author Balu
 * @copyright Copyright (c) 2015 - 2016 Baluart.COM
 * @license http://codecanyon.net/licenses/faq Envato marketplace licenses
 * @link http://easyforms.baluart.com/ Easy Forms
 */

namespace app\helpers;

use Yii;

/**
 * Class TimeHelper
 * @package app\helpers
 */
class TimeHelper
{

    /**
     * Return Time Periods
     *
     * @return array
     */
    public static function timePeriods()
    {
        return [
            'h' => Yii::t('app', 'Hour'),
            'd' => Yii::t('app', 'Day'),
            'w' => Yii::t('app', 'Week'),
            'm' => Yii::t('app', 'Month'),
            'y' => Yii::t('app', 'Year'),
            'a' => Yii::t('app', 'All Time'),
        ];
    }

    /**
     * Return the name of Time Period by its code
     *
     * @param $code
     * @return mixed
     */
    public static function getPeriodByCode($code)
    {
        $periods = self::timePeriods();
        return $periods[$code];
    }

    /**
     * Return the timestamp of the beginning of a period
     *
     * @param $period
     * @return int
     */
    public static function startTime($period)
    {
        switch ($period) {
            case "h":
                // Now modulus 3600 will return the seconds after the start of the hour,
                // then just subtract from the current time.
                $ts = strtotime("now");
                return $ts - ($ts % 3600);
                break;
            case "d":
                // The time is set to 00:00:00
                return strtotime('today');
                break;
            case "w":
                // The code below assumes that the first day of the week is Monday.
                // Period from Monday morning at 00:00:00 to now:
                return mktime(0, 0, 0, date('n'), date('j'), date('Y')) - ((date('N')-1)*3600*24);
                break;
            case "m":
                // Period from the first of the current to now:
                return mktime(0, 0, 0, date('m'), 1, date('Y'));
                break;
            case "y":
                // Period from January 1st to 00:00:00 today:
                return mktime(0, 0, 0, 1, 1, date('Y'));
                break;
            case "a":
                return 0;
                break;
        }
        return 0;
    }
}
