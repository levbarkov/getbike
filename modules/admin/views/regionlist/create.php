<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\RegionList */

$this->title = 'Create Region List';
$this->params['breadcrumbs'][] = ['label' => 'Region Lists', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="region-list-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
