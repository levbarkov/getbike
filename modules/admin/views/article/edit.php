<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Article */

$this->title = $model->title ? 'Article: '.$model->title : 'New article';
$this->params['breadcrumbs'][] = ['label' => 'Articles', 'url' => ['index']];
$model->title ? $this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]] : '';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="article-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'country_list' => $country_list,
        'region_list' => $region_list

    ]) ?>

</div>
