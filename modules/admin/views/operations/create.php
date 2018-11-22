<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Operations */

$this->title = Yii::t('admin/operations', 'Create Operations');
$this->params['breadcrumbs'][] = ['label' => Yii::t('admin/operations', 'Operations'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="operations-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
