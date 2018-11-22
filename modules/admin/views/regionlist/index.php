<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\RegionListSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Region Lists';
$this->params['breadcrumbs'][] = $this->title;
$country_list = \yii\helpers\ArrayHelper::map(\app\models\CountryList::find()->asArray()->all(), 'id', 'text');

?>
<div class="region-list-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

<!--    <p>
        <?/*= Html::a('Create Region List', ['create'], ['class' => 'btn btn-success']) */?>
    </p>-->

    <div class="panel-group" id="accordion">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
                        Add region
                    </a>
                </h4>
            </div>
            <div id="collapseOne" class="panel-collapse collapse">
                <div class="panel-body">
                    <?= $this->render('_form', [
                        'model' => $model,
                        'country_list'=>$country_list,
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

            //'id',
            'text',
            'alias',
            [
                    'attribute' => 'country_id',
                    'format' => 'raw',
                    'value' => function($model){
                        return $model->country->text;
                    },
                    'filter' => $country_list,
            ],
            'coord',
            'tag_line',
            'adress',
            'description',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
