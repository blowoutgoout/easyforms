<?php

namespace app\bundles;

use yii\web\AssetBundle;

/**
 * Class WysiwygBundle
 *
 * @package app\bundles
 */
class WysiwygBundle extends AssetBundle
{
    public $sourcePath = '@bower/summernote/dist';
    public $css = [
        'summernote.css'
    ];
    public $js = [
        'summernote.min.js',
    ];
    public $publishOptions = [
        'forceCopy' => YII_DEBUG,
    ];
    public $depends = [
        'app\bundles\AppBundle', // Load jquery.js and bootstrap.js first
    ];
}
