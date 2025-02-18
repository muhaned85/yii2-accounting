<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%journal_entry_details}}`.
 */
class m230217_000003_create_journal_entry_details_table extends Migration
{
    private $table='test_journal_entry_details';
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // Create table 'journal_entry_details'
        $this->createTable($this->table , [
            'id'               => $this->primaryKey(),
            'journal_entry_id' => $this->integer()->notNull()->comment('رقم القيد المحاسبي الرئيسي'),
            'account_id'       => $this->integer()->notNull()->comment('رقم الحساب'),
            'debit'            => $this->decimal(15, 2)->notNull()->comment('المبلغ المدين'),
            'credit'           => $this->decimal(15, 2)->notNull()->comment('المبلغ الدائن'),
            'created_at'       => $this->integer()->notNull()->comment('تاريخ الإنشاء (Unix timestamp)'),
            'updated_at'       => $this->integer()->notNull()->comment('تاريخ التعديل (Unix timestamp)'),
        ], 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB');

        // Create index for journal_entry_id column for faster lookups
        $this->createIndex(
            'idx-journal_entry_details-journal_entry_id',
            $this->table,
            'journal_entry_id'
        );

        // Create index for account_id column for faster lookups
        $this->createIndex(
            'idx-journal_entry_details-account_id',
          $this->table,
            'account_id'
        );

        // Optionally, add foreign key constraints if your schema supports it
        $this->addForeignKey(
            'fk-journal_entry_details-journal_entry_id',
          $this->table,
            'journal_entry_id',
            $this->table,
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-journal_entry_details-account_id',
            $this->table,
            'account_id',
            '{{%test_accounts}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // Drop foreign key constraints
        $this->dropForeignKey(
            'fk-journal_entry_details-journal_entry_id',
          $this->table
        );
        $this->dropForeignKey(
            'fk-journal_entry_details-account_id',
          $this->table
        );

        // Drop indexes
        $this->dropIndex(
            'idx-journal_entry_details-journal_entry_id',
          $this->table
        );
        $this->dropIndex(
            'idx-journal_entry_details-account_id',
          $this->table
        );

        // Drop the table
        $this->dropTable($this->table);
    }
}
