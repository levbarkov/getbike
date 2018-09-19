<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;

/**
 * Main application asset bundle.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'front/css/site.css',
        'https://fonts.googleapis.com/css?family=Rubik',
        'front/css/jquery-ui.min.css',
        'front/css/normalize.css',
        'front/css/main.css',
        'front/css/icons.css',
        'front/css/custom.css',
    ];
    public $js = [
        'front/js/vendor/modernizr-3.5.0.min.js',
        'front/js/vendor/jquery-ui.datepicker.js',
        'front/js/vendor/slick.min.js',
        'front/js/plugins.js',
        'front/js/main.js',

    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'yii\web\JqueryAsset',
    ];
}
