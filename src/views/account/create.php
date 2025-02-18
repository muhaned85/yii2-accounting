<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model muh\accounting\Models\Account */
/* @var $accounts muh\accounting\Models\Account[] */

$this->title = 'إضافة حساب جديد';
?>
<h1><?= Html::encode($this->title) ?></h1>

<div class="account-form">
    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'balance')->textInput() ?>

    <?= $form->field($model, 'is_active')->dropDownList([1 => 'مفعل', 0 => 'غير مفعل']) ?>

    <?php 
    // إنشاء قائمة لاختيار الحساب الأب (اختياري)
    $parentList = \yii\helpers\ArrayHelper::map($accounts, 'id', function($account) {
        return $account->code . ' - ' . $account->name;
    });
    echo $form->field($model, 'parent_id')->dropDownList(
        $parentList,
        ['prompt' => 'اختر الحساب الأب إذا وجد']
    );
    ?>

    <div class="form-group">
        <?= Html::submitButton('حفظ', ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
