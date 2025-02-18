<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $tree array */

$this->title = 'شجرة الحسابات مع الأرصدة';
?>
<h1><?= Html::encode($this->title) ?></h1>

<div>
    <?php
    // دالة مساعدة لعرض الشجرة بشكل تكراري
    function renderTree($nodes) {
        echo '<ul>';
        foreach ($nodes as $node) {
            /** @var \muh\accounting\Models\Account $account */
            $account = $node['model'];
            echo '<li>';
            echo Html::encode($account->code . ' - ' . $account->name . ' (الرصيد: ' . $account->balance . ')');
            if (!empty($node['children'])) {
                renderTree($node['children']);
            }
            echo '</li>';
        }
        echo '</ul>';
    }
    renderTree($tree);
    ?>
</div>

<p><?= Html::a('إضافة حساب جديد', ['create'], ['class' => 'btn btn-success']) ?></p>
