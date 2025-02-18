<?php

use muh\accounting\Services\CostCenterService;
use muh\accounting\Services\JournalService;

// إنشاء كائن من خدمة مراكز التكلفة
$costCenterService = new CostCenterService();

// استرجاع جميع مراكز التكلفة والبحث عن مركز تكلفة اسمه "المنصة"
$allCostCenters = $costCenterService->getAllCostCenters();
$platformCostCenter = null;

foreach ($allCostCenters as $center) {
    if ($center->name === 'المنصة') {
        $platformCostCenter = $center;
        break;
    }
}

if (!$platformCostCenter) {
    echo "لم يتم العثور على مركز التكلفة 'المنصة'.";
    exit;
}

// نفترض أن لدينا حساب مصروفات مرتبط بمركز التكلفة "المنصة"
// مثلاً: حساب المصروفات (account_id = 701) يجب أن يكون مرتبطاً بمركز التكلفة "المنصة" (أي أن قيمة حقل cost_center_id في جدول accounts تساوي $platformCostCenter->id)
// كما نفترض أن الحساب النقدي أو حساب الخصم هو account_id = 801

// إنشاء كائن من خدمة القيود المحاسبية
$journalService = new JournalService();

// تحديد تاريخ القيد ووصفه
$entryDate = date('Y-m-d');
$description = "تسجيل مصروف قدره 150 دينار تحت مركز تكلفة 'المنصة'";

// تفاصيل الجانب المدين: حساب المصروفات
$debitDetails = [
    ['account_id' => 701, 'amount' => 150],
];

// تفاصيل الجانب الدائن: حساب النقدية أو الالتزامات
$creditDetails = [
    ['account_id' => 801, 'amount' => 150],
];

try {
    // إنشاء القيد المحاسبي باستخدام طريقة القيد المزدوج
    $journalEntry = $journalService->createMultiAccountEntry($entryDate, $description, $debitDetails, $creditDetails);
    echo "تم إنشاء القيد المحاسبي للمصروف تحت مركز تكلفة 'المنصة' بنجاح، رقم القيد: " . $journalEntry->id;
} catch (\Exception $e) {
    echo "حدث خطأ أثناء إنشاء القيد المحاسبي: " . $e->getMessage();
}
/*
شرح المثال:
استرجاع مركز التكلفة "المنصة":

يتم استخدام CostCenterService لاسترجاع جميع مراكز التكلفة.
يتم البحث عن المركز الذي يكون اسمه "المنصة". إذا لم يتم العثور عليه يتم إيقاف العملية برسالة خطأ.
افتراض الحسابات المرتبطة:

نفترض أن حساب المصروفات (مثلاً بمعرف 701) مرتبط بمركز التكلفة "المنصة" (أي أن حقل cost_center_id في جدول الحسابات يحتوي على قيمة معرف مركز التكلفة).
حساب النقدية أو الالتزامات المستخدم في الجانب الدائن هو 801.
إنشاء القيد المحاسبي:

يتم إنشاء قيد محاسبي باستخدام JournalService بواسطة طريقة createMultiAccountEntry.
في هذا القيد، الجانب المدين يُسجل فيه المصروف (150 دينار) والجانب الدائن يُسجل فيه الخصم (150 دينار).
بهذا الشكل تم استخدام ميزة "مركز التكلفة" المضافة في النظام، مع افتراض أن الحسابات تم إعدادها مسبقاً لربطها بمراكز التكلفة المناسبة.


*/