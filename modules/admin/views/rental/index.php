<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\RentalSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Rentals';
$this->params['breadcrumbs'][] = $this->title;
Yii::$app->language = 'ru-RU';
?>
<div class="rental-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <!--    <p>
        <? /*= Html::a('Create Rental', ['create'], ['class' => 'btn btn-success']) */ ?>
    </p>
-->
    <div class="panel-group" id="accordion">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
                        Add rental
                    </a>
                </h4>
            </div>
            <div id="collapseOne" class="panel-collapse collapse">
                <div class="panel-body">
                    <?= $this->render('_form', [
                        'model' => $model,
                        'data' => $data,
                    ]) ?>
                </div>
            </div>
        </div>
    </div>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'name',
            'phone',
            'mail',
            'adress:ntext',
            'radius',
            //'name',
            //'hash',
            [
                    'attribute' => 'hash',
                'format' => 'raw',
                'value' => function($model){
                    return Html::a('Auth Link', \yii\helpers\Url::to('/rental/auth/'.$model->hash));
                }
            ],
            //'balance',
            //'hash',
            [
                    'attribute' => 'region_id',
                    'format' =>'raw',
                    'value' => function($mdel){
        return $mdel->region->text;
                    }

            ],

            [
                'attribute' => 'balance',
                'format' => 'raw',
                'value' => function($model){

        $html = "<p><b>Operations:</b> " . count($model->operations) . "<br>";
        $html.= "<b>Balance:</b> " . Html::a($model->balance, '/admin/operations/ajax?user_id='.$model->id.'&OperationsSearch%5Brental_id%5D='.$model->id, ['data-fancybox-type'=>'iframe','class'=>'fancybox']) . "</p>";
        return $html;
                }
            ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
