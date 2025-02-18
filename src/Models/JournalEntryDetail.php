<?php

namespace muh\accounting\Models;

use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "journal_entry_details".
 *
 * @property int $id المعرف
 * @property int $journal_entry_id رقم القيد المحاسبي الرئيسي
 * @property int $account_id رقم الحساب
 * @property float $debit المبلغ المدين
 * @property float $credit المبلغ الدائن
 * @property int $created_at تاريخ الإنشاء (Unix timestamp)
 * @property int $updated_at تاريخ التعديل (Unix timestamp)
 *
 * @property JournalEntry $journalEntry القيد المحاسبي الرئيسي المرتبط
 * @property Account $account الحساب المرتبط
 */
class JournalEntryDetail extends ActiveRecord
{
    /**
     * اسم الجدول في قاعدة البيانات.
     *
     * @return string
     */
    public static function tableName()
    {
        return '{{%journal_entry_details}}';
    }

    /**
     * القواعد الخاصة بالتحقق من صحة البيانات.
     *
     * @return array
     */
    public function rules()
    {
        return [
            [['journal_entry_id', 'account_id', 'debit', 'credit'], 'required'],
            [['journal_entry_id', 'account_id', 'created_at', 'updated_at'], 'integer'],
            [['debit', 'credit'], 'number'],
            // التأكد من أن قيمة المبلغ المدين والمبلغ الدائن تكون أكبر من أو تساوي 0
            [['debit', 'credit'], 'compare', 'compareValue' => 0, 'operator' => '>='],
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
            'id'               => 'المعرف',
            'journal_entry_id' => 'رقم القيد المحاسبي',
            'account_id'       => 'رقم الحساب',
            'debit'            => 'المبلغ المدين',
            'credit'           => 'المبلغ الدائن',
            'created_at'       => 'تاريخ الإنشاء',
            'updated_at'       => 'تاريخ التعديل',
        ];
    }

    /**
     * إضافة سلوكيات تلقائية للملف مثل تعبئة created_at و updated_at.
     *
     * @return array
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::class,
        ];
    }

    /**
     * علاقة تفاصيل القيد بالقيد المحاسبي الرئيسي.
     *
     * @return \yii\db\ActiveQuery
     */
    public function getJournalEntry()
    {
        return $this->hasOne(JournalEntry::class, ['id' => 'journal_entry_id']);
    }

    /**
     * علاقة تفاصيل القيد بالحساب.
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAccount()
    {
        return $this->hasOne(Account::class, ['id' => 'account_id']);
    }
}
