<?php
/**
 * Copyright (C) Baluart.COM - All Rights Reserved
 *
 * @since 1.3
 * @author Balu
 * @copyright Copyright (c) 2015 - 2016 Baluart.COM
 * @license http://codecanyon.net/licenses/faq Envato marketplace licenses
 * @link http://easyforms.baluart.com/ Easy Forms
 */

namespace app\helpers;

use Yii;
use yii\base\InvalidParamException;

/**
 * Class FileHelper
 * @package app\helpers
 */
class FileHelper extends \yii\helpers\FileHelper
{

    /**
     * List files and directories inside the specified path without dots
     *
     * @param $dir
     * @param bool $dots
     * @return array
     */
    public static function scandir($dir, $dots = false)
    {
        if (!is_dir($dir)) {
            throw new InvalidParamException("The dir argument must be a directory: $dir");
        }
        if (!$dots) {
            return array_diff(scandir($dir), array('..', '.'));
        }
        return scandir($dir);
    }

}
