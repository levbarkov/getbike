<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\CountrySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Country and Regions';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="country-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <div class="panel-group" id="accordion">
        <div class="row">
            <div class="col-md-6">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
                                Add country
                            </a>
                        </h4>
                    </div>
                    <div id="collapseOne" class="panel-collapse collapse">
                        <div class="panel-body">
                            <?= $this->render('_form', [
                                'model' => $country,
                            ]) ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo">
                                Add region
                            </a>
                        </h4>
                    </div>
                    <div id="collapseTwo" class="panel-collapse collapse">
                        <div class="panel-body">
                            <?= $this->render('/regions/_form', [
                                'model' => $region,
                                'country_list' => $country_list
                            ]) ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Country', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'text',
            'iso',
            [
                    'label' => 'Regions',
                'format' => 'Raw',
                'content' => function($model){
                    $region_list = $model->regions;
                    $html = "";
                    if(!empty($region_list)){
                        foreach ($region_list as $val){
                            $html.="<div class='_regions' id='region_id_".$val['id']."'><input value='".$val['text']."' data-region='".$val['id']."' id='region-".$val['id']."'>";
                            $html.="
                            <span class='save_button'  data-region-id='".$val['id']."'  data-action='save'><i class='glyphicon glyphicon-ok'></i> </span>
                            <span class='dell_button' data-region-id='".$val['id']."' data-action='dell'><i class='glyphicon glyphicon-remove'></i> </span>";
                            $html.="</div>";
                        }
                    }
                    $html.="";
                    return $html;
                }
],
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
