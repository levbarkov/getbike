<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\models\OperationsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('admin/operations', 'Operations');
$this->params['breadcrumbs'][] = $this->title;
Yii::$app->language = 'ru-RU'
?>
<div class="operations-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('admin/operations', 'Create Operations'), ['create'], ['class' => 'btn btn-success']) ?>
        <? //= Html::a()?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            //'rental_id',
            ['attribute' => 'rental_id',
                'format' => 'raw',
                'value' => function ($model) {
                    if ($model->rental_id !== null)
                        return Html::a($model->rental->name, '/admin/rental/view?id=' . $model->rental_id, ['target' => '_blank']);
                }],
            ['attribute' => 'order_id',
                'format' => 'raw',
                'value' => function ($model) {
                    return Html::a($model->order_id, '/admin/zakaz/view?id=' . $model->order_id, ['target' => '_blank']);
                }],
            'sum',
            'operations',
            'date',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
