<?php

namespace muh\accounting\Models;

use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "journal_entries".
 *
 * @property int $id المعرف
 * @property string $entry_date تاريخ القيد (بصيغة YYYY-MM-DD)
 * @property string $description وصف القيد
 * @property int $is_reversed حالة عكس القيد (0 = غير معكوس، 1 = معكوس)
 * @property int $created_at تاريخ الإنشاء (Unix timestamp)
 * @property int $updated_at تاريخ التعديل (Unix timestamp)
 *
 * @property JournalEntryDetail[] $details تفاصيل القيد المحاسبي
 */
class JournalEntry extends ActiveRecord
{
    /**
     * اسم الجدول في قاعدة البيانات.
     *
     * @return string
     */
    public static function tableName()
    {
        return '{{%journal_entries}}';
    }
    
    /**
     * القواعد الخاصة بالتحقق من صحة البيانات.
     *
     * @return array
     */
    public function rules()
    {
        return [
            [['entry_date', 'description'], 'required'],
            [['entry_date'], 'date', 'format' => 'php:Y-m-d'],
            [['description'], 'string'],
            [['is_reversed'], 'integer'],
            [['created_at', 'updated_at'], 'integer'],
            // تعيين القيمة الافتراضية لحالة عكس القيد إلى 0 (غير معكوس)
            [['is_reversed'], 'default', 'value' => 0],
        ];
    }
    
    /**
     * تسميات الحقول لعرضها في النماذج والتقارير.
     *
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'id'          => 'المعرف',
            'entry_date'  => 'تاريخ القيد',
            'description' => 'الوصف',
            'is_reversed' => 'حالة عكس القيد',
            'created_at'  => 'تاريخ الإنشاء',
            'updated_at'  => 'تاريخ التعديل',
        ];
    }
    
    /**
     * إضافة سلوكيات تلقائية للملف.
     *
     * @return array
     */
    public function behaviors()
    {
        return [
            // تعبئة created_at و updated_at تلقائياً
            TimestampBehavior::class,
        ];
    }
    
    /**
     * علاقة القيد مع تفاصيل القيد.
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDetails()
    {
        return $this->hasMany(JournalEntryDetail::class, ['journal_entry_id' => 'id']);
    }
}
