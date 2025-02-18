<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%cost_centers}}`.
 */
class m230217_000004_create_cost_centers_table extends Migration
{
    private $table='test_cost_centers';
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // إنشاء جدول "cost_centers" لدعم مركز التكلفة
        $this->createTable($this->table , [
            'id'          => $this->primaryKey(),
            'code'        => $this->string(50)->notNull()->unique()->comment('رمز مركز التكلفة'),
            'name'        => $this->string(255)->notNull()->comment('اسم مركز التكلفة'),
            'description' => $this->text()->null()->comment('وصف مركز التكلفة'),
            'created_at'  => $this->integer()->notNull()->comment('تاريخ الإنشاء'),
            'updated_at'  => $this->integer()->notNull()->comment('تاريخ التعديل'),
        ], 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable($this->table );
    }
}
