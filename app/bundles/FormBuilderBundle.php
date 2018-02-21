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

use Yii;
use yii\web\AssetBundle;

/**
 * Class FormBuilderBundle
 *
 * @package app\bundles
 */
class FormBuilderBundle extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web/static_files';
    public $css = [
        'css/app.min.css',
        'css/prism.min.css',
        'css/form.builder.min.css',
    ];
    public $js = [
        'js/form.builder/lib/require.js',
    ];
    public $depends = [
        'yii\bootstrap\BootstrapAsset',
    ];
    public function init()
    {
        if (YII_ENV_DEV) {
            // For development & debugging
            $this->jsOptions['data-main'] = Yii::getAlias('@web') . "/static_files/js/form.builder/main.js";
        } else {
            // For production
            // Full command. Run from the base directory.
            // r.js -o static_files/js/form.builder/lib/build.js
            $this->jsOptions['data-main'] = Yii::getAlias('@web') . "/static_files/js/form.builder/main-built.js";
        }

        parent::init();
    }
}
