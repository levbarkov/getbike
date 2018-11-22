<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\SourceMessageSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $model \app\models\SourceMessage */

$this->title = Yii::t('admin/sourcemessage', 'Source Messages');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="source-message-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?//= Html::a(Yii::t('admin/sourcemessage', 'Create Source Message'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <div class="panel-group" id="accordion">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
                        Add message
                    </a>
                </h4>
            </div>
            <div id="collapseOne" class="panel-collapse collapse">
                <div class="panel-body">
        <?=$this->render('_form', ['model' => $model])?>
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
            [
                    'attribute' => 'category',
                    'headerOptions' => ['style' => 'width:180px'],
                    'format' => 'raw',
                    'content' => function($model){
                        return $model->category;
                    },
                    'filter' => \yii\helpers\ArrayHelper::map(\app\models\SourceMessage::find()->select('category')->groupBy('category')->asArray()->all(), 'category', 'category')
            ],
            //'message:ntext',
/*            [
                    'label' => 'Translate',
                    'format' => 'raw',
                    'content' => function($model){
                        $html = '';
                        if($model->messages){
                            foreach ($model->messages as $val){
                                $html.="<b>$val->language :</b> <input class='form-control' value='$val->translation'><br>";
                            }
                        }
                        return $html;
                    }
            ], */[
                    'attribute' => 'message',
                    'format' => 'raw',
                    'content' => function($model){
                        $html = "$model->message <br>";
                        if($model->messages){
                            $html.="<div class=\"panel-group\" id=\"accordion\">
  <div class=\"panel panel-default\">
    <div class=\"panel-heading\">
      <h4 class=\"panel-title\">
              <a data-toggle=\"collapse\" data-parent=\"#accordion\" href=\"#collapse$model->id\">
                Locale
              </a>
            </h4>
    </div><form class='update_locale'><input type='hidden' value='$model->id' name='locale_id'><div id=\"collapse$model->id\" class=\"panel-collapse collapse\">
      <div class=\"panel-body\"><div class='row'>";
                            foreach ($model->messages as $val){
                                $html.="<div class='col-md-6'><b>$val->language :</b> <input class='form-control' name='language[$val->language]' value='$val->translation'></div>";
                            }
                            $html.="</div><div class='row'><div class='col-md-2'><button style='margin-top: 10px' type='submit' class='btn btn-success'>Save</button> </div> </div> </form> </div>
    </div>
  </div></div>";
                        }
                        return $html;
                    }
            ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
