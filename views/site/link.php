<?php

/** @var yii\web\View $this */

$this->title = 'denshortlink';

use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html; ?>
<div class="site-index">
    <?php if( Yii::$app->session->hasFlash('msg') ): ?>
        <div class="alert alert-success alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <?php echo Yii::$app->session->getFlash('msg'); ?>
        </div>
    <?php endif;?>




    <?php $form = ActiveForm::begin(['id' => 'linkform']); ?>

    <?= HTML::label('Введите ссылку:','PropertyContactsNew',['class' => 'control-label','required'=>true]); ?>
    <?= Html::input('text', 'url', null, ['class' => 'form-group form-control','id'=>'url']) ?>
    <div class="form-group">
        <?= Html::submitButton('Укоротить', ['class' => 'btn btn-primary', 'name' => 'contact-button']) ?>
    </div>

    <!--<?= $form->field($model, 'short_code', ['enableClientValidation' => false])->textInput(['autofocus' => true]) ?>-->
    <?= HTML::label('Полученная ссылка:','PropertyContactsNew',['class' => 'control-label','required'=>true]); ?>

    <!--<div class="input-group mb-3">
        <div class="input-group-prepend">
            <button class="btn btn-outline-secondary" type="button">копировать</button>
        </div>-->
        <?= Html::input('text', '', null, ['class' => 'form-group form-control','id'=>'short_code']) ?>
    <!--</div>-->




    <?php ActiveForm::end(); ?>
</div>

<?php
$js = <<<JS
    $('form').on('beforeSubmit', function(){
        var url = $(this).find('#url').val();
       // console.log(url);
        //if(!urlvalidation(url)==true){return false};
        var data = $(this).serialize();
        //console.log(url);
        $.ajax({
            url: '/site/getkey',
            type: 'POST',
            data: data,
            success: function(res){
                console.log(res);
                $('#short_code').val(res);
            },
            error: function(request, status, error){
                console.log(request.responseText);
            }
        });
        return false;
    });

function urlvalidation(url){
    var regexp = /^(?:(?:https?|ftp):\/\/)?(?:(?!(?:10|127)(?:\.\d{1,3}){3})(?!(?:169\.254|192\.168)(?:\.\d{1,3}){2})(?!172\.(?:1[6-9]|2\d|3[0-1])(?:\.\d{1,3}){2})(?:[1-9]\d?|1\d\d|2[01]\d|22[0-3])(?:\.(?:1?\d{1,2}|2[0-4]\d|25[0-5])){2}(?:\.(?:[1-9]\d?|1\d\d|2[0-4]\d|25[0-4]))|(?:(?:[a-z\u00a1-\uffff0-9]-*)*[a-z\u00a1-\uffff0-9]+)(?:\.(?:[a-z\u00a1-\uffff0-9]-*)*[a-z\u00a1-\uffff0-9]+)*(?:\.(?:[a-z\u00a1-\uffff]{2,})))(?::\d{2,5})?(?:\/\S*)?$/;
    if (url != "") {
        if (!regexp.test(url)) {
            alert("Введите корректный url");
        } 
    }else{return true}
}
JS;

$this->registerJs($js);
?>
