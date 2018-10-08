<?php

/* @var $this yii\web\View */
/* @var $model \app\models\Pages */
use yii\helpers\Html;

$this->title = $model->page_title ? $model->page_title : 'getbike.io - empty page :(';
?>
<?php if($model->page_css) echo "<style>$model->page_css</style>"; ?>
<div class="content">
    <?=$model->page_code ? $model->page_code : ''?>
</div>
<?php if($model->page_js) echo "<script>window.onload=function (ev) { $model->page_js }</script>"; ?>
