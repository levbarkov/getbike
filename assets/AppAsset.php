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
        'css/site.css',
        'https://fonts.googleapis.com/css?family=Rubik',      
        'css/jquery-ui.min.css',
        'css/normalize.css',
        'css/main.css',
        'css/icons.css',
        'css/custom.css',     
        //'//code.jquery.com/ui/1.12.1/themes/ui-lightness/jquery-ui.css',          
    ];
    public $js = [
        'js/vendor/modernizr-3.5.0.min.js',
        'js/vendor/jquery-ui.datepicker.js',
        //'//code.jquery.com/ui/1.12.1/jquery-ui.js',
        'js/vendor/slick.min.js',
        'js/plugins.js',
        'js/main.js',
            
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'yii\web\JqueryAsset',
    ];
}
