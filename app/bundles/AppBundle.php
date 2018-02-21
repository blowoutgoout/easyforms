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

namespace app\bundles;

use yii\web\AssetBundle;

/**
 * Class AppBundle
 *
 * @package app\bundles
 */
class AppBundle extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web/static_files';
    public $css = [
        'css/app.min.css',
    ];
    public $js = [
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'kartik\switchinput\SwitchInputAsset',
    ];
}
