<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\ArticleSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Articles';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="article-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Article', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'title:ntext',
            'en_title:ntext',
            //'country_id',
            //'region_id',
            [
                    'attribute' => 'country_id',
                    'format' => 'raw',
                    'content' => function($model){
                        /* @var $model \app\models\Article */
                        return $model->country->text;
                    }
            ],  [
                    'attribute' => 'region_id',
                    'format' => 'raw',
                    'content' => function($model){
                        /* @var $model \app\models\Article */
                        return $model->region->text;
                    }
            ],
            [
                    'label' => 'Link',
                'format' => 'raw',
                'content' => function($model){
                   if($model->region_id && $model->country_id){
                       $country = \app\models\Country::findOne(['id' => $model->country_id]);
                       $region = \app\models\Regions::findOne(['id' => $model->region_id]);
                       if($region){
                           $region = $region->text;
                       }else{
                           return 'Country or region not selected';
                       }
                       return Html::a($model->title,"/info/$country->iso/$region/$model->en_title");
                   }
                   return 'Country or region not selected';

                }
            ],
           // 'text:ntext',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
