<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\BikesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Bikes';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bikes-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

   <!-- <p>
        <?/*= Html::a('Create Bikes', ['create'], ['class' => 'btn btn-success']) */?>
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
                    <?= $this->render('_form', [
                        'model' => $model,
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
            'model',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
