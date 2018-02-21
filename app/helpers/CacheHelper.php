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
 * Class CacheHelper
 * @package app\helpers
 */
class CacheHelper
{

    public static function cache($key, $duration, $callable)
    {

        $cache = Yii::$app->cache;

        if ($cache->exists($key)) {

            $data = $cache->get($key);

        } else {

            $data = $callable();

            if ($data) {

                $cache->set($key, $data, $duration);

            }
        }

        return $data;
    }

    public static function getLocale()
    {
        return strtolower(substr(Yii::$app->language, 0, 2));
    }
}
