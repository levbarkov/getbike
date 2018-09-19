<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\BikesPriceSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Bikes Prices';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bikes-price-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

<!--    <p>
        <?/*= Html::a('Create Bikes Price', ['create'], ['class' => 'btn btn-success']) */?>
    </p>-->
    <div class="panel-group" id="accordion">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
                        Add bike
                    </a>
                </h4>
            </div>
            <div id="collapseOne" class="panel-collapse collapse">
                <div class="panel-body">
                    <?= $this->render('../bikes/_form', [
                        'model' => $bikes,
                    ]) ?>
                </div>
            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo">
                        Add bike price
                    </a>
                </h4>
            </div>
            <div id="collapseTwo" class="panel-collapse collapse">
                <div class="panel-body">
                    <?= $this->render('_form', [
                        'model' => $model,
                        'data' => $data,
                    ]) ?>                </div>
            </div>
        </div>
    </div>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            //'bike.model',
            [
                    'attribute' => 'bike_id',
                'format' => 'raw',
                'value' => function($model){
        return $model->bike->model;
                },
                'filter' => $data['bike_list']
            ],
            //'condition_id',
            [
                'attribute' => 'condition_id',
                'format' => 'raw',
                'value' => function($model){
                    return $model->condition->text;
                },
                'filter' => $data['condition_list']
            ],
            'photo',
            'price',
            'pricepm',
            //'region_id',
            [
                'attribute' => 'region_id',
                'format' => 'raw',
                'value' => function($model){
                    return $model->region->text;
                },
                'filter' => $data['region_list']
            ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
