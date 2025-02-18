<?php

use muh\accounting\Services\JournalService;

// نحدد معرف القيد الذي نريد عكسه، على سبيل المثال:
$journalEntryId = 123; // قم بتعديل المعرف حسب القيد المراد عكسه

// إنشاء كائن من خدمة القيود المحاسبية
$journalService = new JournalService();

try {
    // محاولة عكس القيد
    $reversalEntry = $journalService->reverseEntry($journalEntryId);
    echo "تم عكس القيد بنجاح، رقم القيد المعكوس: " . $reversalEntry->id;
} catch (\Exception $e) {
    // في حالة حدوث خطأ يتم عرض رسالة الخطأ
    echo "حدث خطأ أثناء عكس القيد: " . $e->getMessage();
}
/*
```php
<?php

use muh\accounting\Services\JournalService;

// نحدد معرف القيد الذي نريد عكسه، على سبيل المثال:
$journalEntryId = 123; // قم بتعديل المعرف حسب القيد المراد عكسه

// إنشاء كائن من خدمة القيود المحاسبية
$journalService = new JournalService();

try {
    // محاولة عكس القيد
    $reversalEntry = $journalService->reverseEntry($journalEntryId);
    echo "تم عكس القيد بنجاح، رقم القيد المعكوس: " . $reversalEntry->id;
} catch (\Exception $e) {
    // في حالة حدوث خطأ يتم عرض رسالة الخطأ
    echo "حدث خطأ أثناء عكس القيد: " . $e->getMessage();
}
```

### شرح المثال:
1. **تحديد معرف القيد:**  
   - قمنا بتحديد معرف القيد الذي نريد عكسه في المتغير `$journalEntryId`.  
2. **إنشاء كائن من خدمة القيود:**  
   - تم إنشاء كائن من `JournalService` الذي يحتوي على دالة `reverseEntry()` لعكس القيد.  
3. **محاولة عكس القيد:**  
   - يتم استدعاء دالة `reverseEntry()` وتمرير معرف القيد.  
   - إذا نجحت العملية، يتم طباعة رقم القيد المعكوس.  
4. **التعامل مع الأخطاء:**  
   - في حالة حدوث خطأ، يتم التقاط الاستثناء وعرض رسالة الخطأ المناسبة.

بهذا المثال يمكنك عكس قيد معين في نظامك المحاسبي.
*/