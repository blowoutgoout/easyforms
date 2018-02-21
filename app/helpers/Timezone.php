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
 * Class Timezone
 * @package app\helpers
 */
class Timezone
{
    const SORT_NAME   = 0;
    const SORT_OFFSET = 1;
    public static $template = '{name} {offset}';
    public static $sortBy = 0;

    public static function all()
    {
        $timeZones = [];
        $timeZonesOutput = [];
        $now = new \DateTime('now', new \DateTimeZone('UTC'));
        foreach (\DateTimeZone::listIdentifiers(\DateTimeZone::ALL) as $timeZone) {
            $now->setTimezone(new \DateTimeZone($timeZone));
            $timeZones[] = [$now->format('P'), $timeZone];
        }
        if (self::$sortBy == static::SORT_OFFSET) {
            array_multisort($timeZones);
        }

        foreach ($timeZones as $timeZone) {
            $content = preg_replace_callback("/{\\w+}/", function ($matches) use ($timeZone) {
                switch ($matches[0]) {
                    case '{name}':
                        return $timeZone[1];
                    case '{offset}':
                        return $timeZone[0];
                    default:
                        return $matches[0];
                }
            }, self::$template);
            $timeZonesOutput[$timeZone[1]] = $content;
        }

        return $timeZonesOutput;
    }
}
