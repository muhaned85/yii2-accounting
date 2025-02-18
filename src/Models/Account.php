<?php

namespace muh\accounting\Models;

use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "accounts".
 *
 * @property int $id
 * @property string $code           رمز الحساب (يجب أن يكون فريدًا)
 * @property string $name           اسم الحساب
 * @property string|null $description وصف الحساب
 * @property float|null $balance    الرصيد الحالي
 * @property int $is_active         حالة تفعيل الحساب (1 مفعل، 0 غير مفعل)
 * @property int|null $parent_id    معرف الحساب الأب (لدعم الشجرة)
 * @property int $created_at        تاريخ الإنشاء (توقيت Unix)
 * @property int $updated_at        تاريخ التعديل (توقيت Unix)
 *
 * @property Account $parent      الحساب الأب
 * @property Account[] $children  الحسابات الفرعية
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
            [['parent_id'], 'integer'],
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
            'parent_id'   => 'الحساب الأب',
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

    /**
     * علاقة الحساب الأب.
     *
     * @return \yii\db\ActiveQuery
     */
    public function getParent()
    {
        return $this->hasOne(self::class, ['id' => 'parent_id']);
    }

    /**
     * علاقة الحسابات الفرعية.
     *
     * @return \yii\db\ActiveQuery
     */
    public function getChildren()
    {
        return $this->hasMany(self::class, ['parent_id' => 'id']);
    }
}
