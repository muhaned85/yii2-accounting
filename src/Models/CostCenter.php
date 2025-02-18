<?php

namespace muh\accounting\Models;

use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "cost_centers".
 *
 * @property int $id
 * @property string $code         رمز مركز التكلفة (يجب أن يكون فريدًا)
 * @property string $name         اسم مركز التكلفة
 * @property string|null $description وصف مركز التكلفة
 * @property int $created_at      تاريخ الإنشاء (توقيت Unix)
 * @property int $updated_at      تاريخ التعديل (توقيت Unix)
 */
class CostCenter extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%cost_centers}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['code', 'name'], 'required'],
            [['code'], 'string', 'max' => 50],
            [['name'], 'string', 'max' => 255],
            [['description'], 'string'],
            [['created_at', 'updated_at'], 'integer'],
            [['code'], 'unique'], // لضمان عدم تكرار رمز مركز التكلفة
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id'          => 'المعرف',
            'code'        => 'رمز مركز التكلفة',
            'name'        => 'اسم مركز التكلفة',
            'description' => 'الوصف',
            'created_at'  => 'تاريخ الإنشاء',
            'updated_at'  => 'تاريخ التعديل',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            // يقوم بتعبئة created_at و updated_at تلقائيًا
            TimestampBehavior::class,
        ];
    }
}
