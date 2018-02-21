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
 * Class RulesBuilderBundle
 *
 * @package app\bundles
 */
class RulesBuilderBundle extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web/static_files';
    public $css = [
        'css/awesome-bootstrap-checkbox.min.css',
        'css/rules.builder.min.css'
    ];
    public $js = [
        'js/libs/underscore.js',
        'js/libs/backbone.js',
        'js/libs/bootstrap-notify.min.js',
        'js/rules.builder.operators.min.js',
        'js/rules.builder.conditions.min.js',
        'js/rules.builder.actions.min.js',
        'js/rules.builder.app.min.js',
    ];
    public $depends = [
        'yii\bootstrap\BootstrapPluginAsset', // Load jquery.js and bootstrap.js first
        'yii\jui\JuiAsset', // It includes the CSS and JavaScript files from the jQuery UI library
    ];
}
