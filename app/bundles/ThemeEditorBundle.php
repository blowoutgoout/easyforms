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
 * Class ThemeEditorBundle
 *
 * @package app\bundles
 */
class ThemeEditorBundle extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web/static_files';
    public $css = [
    ];
    public $js = [
        'js/libs/ace.js',
        'js/theme.editor.min.js',
    ];
    public $depends = [
        'yii\bootstrap\BootstrapPluginAsset', // Load jquery.js and bootstrap.js first
    ];
}
