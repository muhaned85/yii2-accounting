<?php

namespace muh\accounting\Models;

use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "accounts".
 *
 * @property int $id
 * @property string $code         رمز الحساب (يجب أن يكون فريدًا)
 * @property string $name         اسم الحساب
 * @property string|null $description وصف الحساب
 * @property float|null $balance   الرصيد الحالي
 * @property int $is_active       حالة تفعيل الحساب (1 مفعل، 0 غير مفعل)
 * @property int $created_at      تاريخ الإنشاء (توقيت Unix)
 * @property int $updated_at      تاريخ التعديل (توقيت Unix)
 */
class Account extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%accounts}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['code', 'name'], 'required'],
            [['code'], 'string', 'max' => 50],
            [['name'], 'string', 'max' => 255],
            [['description'], 'string'],
            [['balance'], 'number'],
            [['is_active'], 'integer'],
            [['created_at', 'updated_at'], 'integer'],
            [['code'], 'unique'], // لضمان عدم تكرار رمز الحساب
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'          => 'المعرف',
            'code'        => 'رمز الحساب',
            'name'        => 'اسم الحساب',
            'description' => 'الوصف',
            'balance'     => 'الرصيد الحالي',
            'is_active'   => 'الحالة',
            'created_at'  => 'تاريخ الإنشاء',
            'updated_at'  => 'تاريخ التعديل',
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            // يقوم بتعبئة created_at و updated_at تلقائيًا
            TimestampBehavior::class,
        ];
    }
}
