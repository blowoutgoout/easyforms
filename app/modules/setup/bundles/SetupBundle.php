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

namespace app\modules\setup\bundles;

use yii\web\AssetBundle;

class SetupBundle extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web/static_files';
    public $css = [
        'css/setup.min.css',
    ];
    public $depends = [
        'yii\bootstrap\BootstrapAsset',
        'yii\bootstrap\BootstrapPluginAsset', // Load jquery.js and bootstrap.js first
    ];
}
