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
 * Class SubmissionsReportBundle
 *
 * @package app\bundles
 */
class SubmissionsReportBundle extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web/static_files';
    public $css = [
        'css/gridstack.css',
    ];
    public $js = [
        'js/libs/jquery-ui.js',
        'js/libs/lodash.min.js',
        'js/libs/gridstack.js',
        'js/submissions.report.min.js',
    ];
    public $depends = [
        'app\bundles\VisualizationBundle', // Load d3.js, crossfilter.js and dc.js first
    ];
}
