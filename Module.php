<?php

namespace muh\accounting;

use yii\base\Module as BaseModule;

/**
 * Module class for the accounting extension.
 */
class Module extends BaseModule
{
    // يمكنك تعريف معرف الوحدة (module ID) الخاص بها
    public $controllerNamespace = 'muh\accounting\controllers';

    /**
     * تقوم الدالة init بتهيئة الوحدة.
     */
    public function init()
    {
        parent::init();
        // هنا يمكن وضع إعدادات خاصة بالوحدة أو تحميل ملفات التهيئة
    }
}
