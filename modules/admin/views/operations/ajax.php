<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\modules\admin\AppAsset;
/* @var $this yii\web\View */
/* @var $searchModel app\models\OperationsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('admin/operations', 'Operations');
$this->params['breadcrumbs'][] = $this->title;
AppAsset::register($this);
?>
<div class="operations-index">
    <h3>Operation list</h3>
    <p>
        <?= Html::a(Yii::t('admin/operations', 'Create Operations'), ['/admin/operations/add?id='.$user_id], ['class' => 'btn btn-success']) ?>
        <?//= Html::a()?>
    </p>
    <?php Pjax::begin(); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            //'rental_id',
            //'order_id',
            ['attribute' => 'order_id',
                'format' => 'raw',
                'value' => function($model){
        return  Html::a($model->order_id, '/admin/zakaz/view?id='.$model->order_id, ['target'=>'_blank']);
                }],
            //'sum',
            [
                    'attribute' => 'sum',
                    'format' => 'raw',
                'value' => function ($model){
        return $model->sum . ' Rp';
                }

],
            'operations',
            'date',

            //['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
