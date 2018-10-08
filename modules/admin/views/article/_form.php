<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use dosamigos\tinymce\TinyMce;
use yii\web\JsExpression;

/* @var $this yii\web\View */
/* @var $model app\models\Article */
/* @var $form yii\widgets\ActiveForm */
?>
<script>
    window.onload = function () {

        $('#article-country_id').change(function () {
           var val = $(this).val();
           if(val != ''){
               $.ajax({
                   type: "POST",
                   url: '/admin/country/getregions?id=' + val,
                   success: function (data) {
                        if(data != 0){
                            $('#article-region_id').html('')
                                .append(data);
                            return true;
                        }
                   }
               });
           }
            $('#article-region_id').html('<option value>Значение не выбрано</option>');
           return true;
        });
    }
</script>

<div class="article-form">



    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-md-6">    <?= $form->field($model, 'title')->textInput() ?>
        </div>
        <div class="col-md-6">    <?= $form->field($model, 'en_title')->textInput() ?>
        </div>
        <div class="col-md-6">    <?= $form->field($model, 'page_title')->textInput() ?>
        </div>
        <div class="col-md-6">    <?= $form->field($model, 'page_desc')->textInput() ?>
        </div>
        <div class="col-md-6">    <?= $form->field($model, 'country_id')->dropDownList($country_list,['prompt'=>'Значение не выбрано']) ?>
        </div>
        <div class="col-md-6">    <?= $form->field($model, 'region_id')->dropDownList($region_list,['prompt'=>'Значение не выбрано']) ?>
        </div>
    </div>

    <?= $form->field($model, 'text')->widget(TinyMce::className(), [
    'options' => ['rows' => 16],
    'language' => 'en_GB',
        'clientOptions' => [
            'plugins' => [
                "advlist autolink lists link charmap print preview anchor",
                "searchreplace visualblocks code fullscreen",
                "insertdatetime media table contextmenu paste"
            ],
            'toolbar' => "undo redo | styleselect fontsizeselect  | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image"
        ]
]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
