<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Bikes */

$this->title = 'Create Bikes';
$this->params['breadcrumbs'][] = ['label' => 'Bikes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bikes-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
