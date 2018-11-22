<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\SourceMessage */

$this->title = Yii::t('admin/sourcemessage', 'Update Source Message: {nameAttribute}', [
    'nameAttribute' => $model->id,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('admin/sourcemessage', 'Source Messages'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('admin/sourcemessage', 'Update');
?>
<div class="source-message-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
