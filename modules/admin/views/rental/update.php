<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model app\models\Rental */
/* @var $searchModel \app\models\OperationsSearch */
/* @var $dataProvider \app\models\OperationsSearch */


$this->title = 'Update Rental: '.$model->name;
$this->params['breadcrumbs'][] = ['label' => 'Rentals', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="rental-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'data' => $data,
    ]) ?>

    <?= $this->context->renderPartial('/operations/ajax', [
        'searchModel' => $searchModel,
        'dataProvider' => $dataProvider,
        'user_id' => $model->id
    ])?>

    <p style="font-size: 1.8rem"><strong>Current balance:</strong>  <?=$model->balance?> Rp</p>

</div>
