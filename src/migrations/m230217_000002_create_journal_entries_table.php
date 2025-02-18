<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%journal_entries}}`.
 */
class m230217_000002_create_journal_entries_table extends Migration
{
    private $table ='test_journal_entries';
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // Create table 'journal_entries'
        $this->createTable($this->table , [
            'id'          => $this->primaryKey(),
            'entry_date'  => $this->date()->notNull()->comment('تاريخ القيد (بصيغة YYYY-MM-DD)'),
            'description' => $this->text()->notNull()->comment('وصف القيد'),
            'is_reversed' => $this->smallInteger()->notNull()->defaultValue(0)->comment('حالة عكس القيد (0 = غير معكوس، 1 = معكوس)'),
            'created_at'  => $this->integer()->notNull()->comment('تاريخ الإنشاء (Unix timestamp)'),
            'updated_at'  => $this->integer()->notNull()->comment('تاريخ التعديل (Unix timestamp)'),
        ], 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB');

        // You may add additional indexes if needed.
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable($this->table);
    }
}
