<?php
/**
 * Created by PhpStorm.
 * User: trufanov
 * Date: 16.04.2018
 * Time: 16:53
 */

namespace app\modules\rental;

use yii\web\AssetBundle;

class AppAsset extends AssetBundle
{

    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/jquery-ui.min.css',
        'css/normalize.css',
        'css/main.css',
        'css/icons.css',
        'css/custom.css',
        'css/alerts.css',
    ];
    public $js = [
        'js/vendor/modernizr-3.5.0.min.js',
        '//code.jquery.com/jquery-3.2.1.min.js',
        'js/vendor/jquery-ui.datepicker.js',
        'js/plugins.js',
        'js/main.js',
        'js/alerts.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        //'yii\bootstrap\BootstrapAsset',
    ];
}