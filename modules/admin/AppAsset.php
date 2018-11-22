<?php
/**
 * Created by PhpStorm.
 * User: trufanov
 * Date: 16.04.2018
 * Time: 16:53
 */

namespace app\modules\admin;

use yii\web\AssetBundle;

class AppAsset extends AssetBundle
{

    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/site.css',
        '//cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.7/css/jquery.fancybox.min.css',
        'css/admin.css',
    ];
    public $js = [
        '//cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.7/js/jquery.fancybox.min.js',
        'js/admin.js'
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}