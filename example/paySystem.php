<?php

use muh\accounting\Models\Account;

// إنشاء حساب "بنك مصرف ليبيا المركزي" الخاص بايداع الرواتب من وزارة المالية
$libyaCentralBank = new Account();
$libyaCentralBank->code = 'LBCB001';
$libyaCentralBank->name = 'بنك مصرف ليبيا المركزي';
$libyaCentralBank->description = 'خاص بايداع قيمة الرواتب من وزارة المالية';
$libyaCentralBank->balance = 0;
$libyaCentralBank->is_active = 1;

if (!$libyaCentralBank->save()) {
    echo "حدث خطأ أثناء إنشاء حساب بنك مصرف ليبيا المركزي: " . implode(', ', $libyaCentralBank->getFirstErrors());
} else {
    echo "تم إنشاء حساب بنك مصرف ليبيا المركزي بنجاح.<br>";
}

// إنشاء حساب "بنك مصرف الجمهورية" الخاص بصرف السلف للجنود
$republicBank = new Account();
$republicBank->code = 'RBB001';
$republicBank->name = 'بنك مصرف الجمهورية';
$republicBank->description = 'خاص بصرف السلف للجنود';
$republicBank->balance = 0;
$republicBank->is_active = 1;

if (!$republicBank->save()) {
    echo "حدث خطأ أثناء إنشاء حساب بنك مصرف الجمهورية: " . implode(', ', $republicBank->getFirstErrors());
} else {
    echo "تم إنشاء حساب بنك مصرف الجمهورية بنجاح.<br>";
}

// إنشاء حساب فرعي تحت "بنك مصرف الجمهورية" ليتولى تحصيل السلف
$advanceCollection = new Account();
$advanceCollection->code = 'RBB-COL';
$advanceCollection->name = 'تحصيل السلف - بنك مصرف الجمهورية';
$advanceCollection->description = 'يتم تحصيل السلف في هذا الحساب ويتم تحويل القيمة المتحصلة نهاية الشهر إلى بنك مصرف الجمهورية';
$advanceCollection->parent_id = $republicBank->id; // ربط الحساب الفرعي بالحساب الأب "بنك مصرف الجمهورية"
$advanceCollection->balance = 0;
$advanceCollection->is_active = 1;

if (!$advanceCollection->save()) {
    echo "حدث خطأ أثناء إنشاء حساب تحصيل السلف: " . implode(', ', $advanceCollection->getFirstErrors());
} else {
    echo "تم إنشاء حساب تحصيل السلف تحت بنك مصرف الجمهورية بنجاح.";
}
