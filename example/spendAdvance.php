<?php

use muh\accounting\Services\JournalService;

// نفترض أن معرفات الحسابات معدة مسبقاً كالتالي:
// - حساب سلفة الموظفين: account_id = 901 (يعتبر حساب أصول يمثل المبالغ المُسلفة)
// - حساب النقدية/البنك: account_id = 801 (يُستخدم لتسديد المبالغ النقدية أو من البنك)

// إنشاء كائن من خدمة القيود المحاسبية
$journalService = new JournalService();

// تحديد تاريخ القيد ووصف العملية
$entryDate = date('Y-m-d');
$description = "صرف سلفة لموظف بمبلغ 500 دينار";

// في هذه العملية، يتم تسجيل:
// - زيادة في حساب سلفة الموظفين (مدين) أي أنه يُسجل كأصل للشركة
// - انخفاض في حساب النقدية/البنك (دائن)
$debitDetails = [
    ['account_id' => 901, 'amount' => 500],
];

$creditDetails = [
    ['account_id' => 801, 'amount' => 500],
];

try {
    // إنشاء القيد المحاسبي باستخدام طريقة القيد المزدوج
    $journalEntry = $journalService->createMultiAccountEntry($entryDate, $description, $debitDetails, $creditDetails);
    echo "تم إنشاء قيد صرف السلفة بنجاح، رقم القيد: " . $journalEntry->id;
} catch (\Exception $e) {
    echo "حدث خطأ أثناء إنشاء قيد صرف السلفة: " . $e->getMessage();
}
/*
```php
<?php

use muh\accounting\Services\JournalService;

// نفترض أن معرفات الحسابات معدة مسبقاً كالتالي:
// - حساب سلفة الموظفين: account_id = 901 (يعتبر حساب أصول يمثل المبالغ المُسلفة)
// - حساب النقدية/البنك: account_id = 801 (يُستخدم لتسديد المبالغ النقدية أو من البنك)

// إنشاء كائن من خدمة القيود المحاسبية
$journalService = new JournalService();

// تحديد تاريخ القيد ووصف العملية
$entryDate = date('Y-m-d');
$description = "صرف سلفة لموظف بمبلغ 500 دينار";

// في هذه العملية، يتم تسجيل:
// - زيادة في حساب سلفة الموظفين (مدين) أي أنه يُسجل كأصل للشركة
// - انخفاض في حساب النقدية/البنك (دائن)
$debitDetails = [
    ['account_id' => 901, 'amount' => 500],
];

$creditDetails = [
    ['account_id' => 801, 'amount' => 500],
];

try {
    // إنشاء القيد المحاسبي باستخدام طريقة القيد المزدوج
    $journalEntry = $journalService->createMultiAccountEntry($entryDate, $description, $debitDetails, $creditDetails);
    echo "تم إنشاء قيد صرف السلفة بنجاح، رقم القيد: " . $journalEntry->id;
} catch (\Exception $e) {
    echo "حدث خطأ أثناء إنشاء قيد صرف السلفة: " . $e->getMessage();
}
```

### شرح المثال:
1. **تحديد الحسابات:**
   - **901:** حساب سلفة الموظفين، حيث يتم تسجيل المبالغ المُسلفة كأصول للشركة.
   - **801:** حساب النقدية/البنك، والذي يتم خصمه عند صرف السلفة.

2. **تفاصيل العملية:**
   - في الجانب المدين: يُسجل مبلغ 500 دينار في حساب سلفة الموظفين.
   - في الجانب الدائن: يُسجل نفس المبلغ (500 دينار) في حساب النقدية/البنك لتقليل رصيد النقد.

3. **تنفيذ القيد المحاسبي:**
   - يتم استخدام دالة `createMultiAccountEntry` من خدمة القيود لإنشاء قيد محاسبي متوازن.
   - في حال نجاح العملية، يتم عرض رقم القيد المحاسبي الذي تم إنشاؤه.
   - في حالة حدوث خطأ، يتم عرض رسالة الخطأ المناسبة.

بهذا المثال يتم تسجيل عملية صرف سلفة لموظف في النظام المحاسبي باستخدام ميزة القيد المزدوج.
*/