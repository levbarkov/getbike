<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\ZakazSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Orders';
$this->params['breadcrumbs'][] = $this->title;
global  $status_list;
global $rental_list;

$rental_list = \app\models\Rental::find()->select(['id', 'name'])->asArray()->all();
$status_list = ['Оформлен', 'Доставлен', 'Оплачен', 'Отменён', 'Передан рентальщику'];
?>
<div class="zakaz-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Zakaz', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            //'rental_id',
            [
                    'attribute' => 'rental_id',
                    'format' => 'raw',
                'headerOptions' => ['width' => '140'],
                'value' => function ($model, $key, $index, $grid) {
        /* @var $model \app\models\Zakaz */
                    $select = '<select class="form-control" data-action="change_rental" data-lead="' . $model->id . '">';
                    $select.= '<option value></option>';
                    global $rental_list;
                    foreach ($rental_list as $rental) {
                        $select .= '<option ';
                        if ($model->rental_id == $rental['id']) $select .= 'selected';
                        $select .= ' value="' . $rental['id'] . '">' . $rental['name'] . '</option>';
                    }
                    $select .= '</select>';
                    //return $model->status0->text;
                    return $select;
                },
                'filter' => \yii\helpers\ArrayHelper::map($rental_list, 'id', 'name'),
            ],
            'user_name',
            'user_phone',
            'user_email:email',
            'zakaz_info',
            //'garage_id',
            //'date_for',
            //'date_to',
            'curr_date',
            'service_tax',
            //'pay_id',
            //'region_id',
            [
                'attribute' => 'status',
                'format' => 'raw',
                'headerOptions' => ['width' => '140'],
                'value' => function ($model, $key, $index, $grid) {
                    $select = '<select class="form-control" data-action="change_lead_status" data-lead="' . $model->id . '">';
                    global $status_list;
                    foreach ($status_list as $k=>$v) {
                        $select .= '<option ';
                        if ($model->status == $k) $select .= 'selected';
                        $select .= ' value="' . $k . '">' . $v . '</option>';
                    }
                    $select .= '</select>';
                    //return $model->status0->text;
                    return $select;
                },
                'filter' => $status_list,
                // 'filter' => Html::activeDropDownList($searchModel, 'status',
                //             $this->getStatus(), ['class' => 'form-control', 'multiple' => true]),
            ],

            'comment',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
