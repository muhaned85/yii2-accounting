<?php

use muh\accounting\Models\Account;

// افتراضاً أن لديك معرف الحساب الرئيسي الذي تريد إضافة الحساب الفرعي تحته
$parentAccountId = 5; // على سبيل المثال، معرف الحساب الرئيسي هو 5

// إنشاء حساب جديد
$account = new Account();
$account->code = '2001'; // رمز الحساب الجديد
$account->name = 'حساب فرعي للمبيعات'; // اسم الحساب الجديد
$account->description = 'هذا الحساب فرعي تحت حساب المبيعات الرئيسي'; // وصف اختياري
$account->balance = 0; // الرصيد الابتدائي
$account->is_active = 1; // تفعيل الحساب
$account->parent_id = $parentAccountId; // ربط الحساب الجديد بالحساب الرئيسي

// في حال كنت تستخدم مركز تكلفة، يمكنك تحديد معرف مركز التكلفة هنا أيضاً
// $account->cost_center_id = 2; // على سبيل المثال، معرف مركز التكلفة هو 2

if ($account->save()) {
    echo "تم إنشاء الحساب الفرعي بنجاح.";
} else {
    echo "حدث خطأ أثناء إنشاء الحساب: " . implode(', ', $account->getFirstErrors());
}
