<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\RentalGarageSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Rental Garages';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="rental-garage-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?//= Html::a('Create Rental Garage', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            'rental.name',
            'bike.model',
            //'condition.text',
            [
                'attribute' => 'condition_id',
                'label' => 'Condition',
                'format' => 'raw',
                'value' => function($model){
                    return $model->condition->text;
                }
            ],
            'number',
            //'status',
            'year',
            'millage',
            'radius',
            //'region.text',
            [
                'attribute' => 'region_id',
                'label' => 'Region',
                'format' => 'raw',
                'value' => function($model){
                    return $model->region->text;
                }
            ],
            //'status',
            [
              'attribute' => 'status',
              'format' => 'raw',
              'value' => function($model){
                    if($model->status == 0 ){
                        return 'Off';
                    }
                    return 'On';
              }
            ],

            //['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
