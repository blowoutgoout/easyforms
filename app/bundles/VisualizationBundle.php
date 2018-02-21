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
 * Class VisualizationBundle
 *
 * @package app\bundles
 */
class VisualizationBundle extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web/static_files';
    public $css = [
        'css/dc.min.css'
    ];
    public $js = [
        'js/libs/d3.min.js', // v3.5.6
        'js/libs/crossfilter.min.js', // v1.3.12
        'js/libs/dc.min.js', // v2.0.0-beta19
    ];
    public $depends = [
        'yii\bootstrap\BootstrapPluginAsset', // Load jquery.js and bootstrap.js first
    ];
}
