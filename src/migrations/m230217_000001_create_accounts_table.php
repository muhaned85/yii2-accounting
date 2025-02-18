<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%test_accounts}}`.
 * This version supports the chart of accounts tree structure by adding a `parent_id` column,
 * and seeds the table with main accounts.
 */
class m230217_000001_create_accounts_table extends Migration
{
    private $table = 'test_accounts';
    
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // Create table 'test_accounts'
        $this->createTable($this->table, [
            'id'          => $this->primaryKey(),
            'code'        => $this->string(50)->notNull()->unique()->comment('رمز الحساب'),
            'name'        => $this->string(255)->notNull()->comment('اسم الحساب'),
            'description' => $this->text()->null()->comment('وصف الحساب'),
            'balance'     => $this->decimal(15, 2)->null()->defaultValue(0)->comment('الرصيد الحالي'),
            'is_active'   => $this->smallInteger()->notNull()->defaultValue(1)->comment('حالة تفعيل الحساب (1 مفعل، 0 غير مفعل)'),
            'parent_id'   => $this->integer()->null()->comment('معرّف الحساب الأب (لدعم شجرة الحسابات)'),
            'created_at'  => $this->integer()->notNull()->comment('تاريخ الإنشاء'),
            'updated_at'  => $this->integer()->notNull()->comment('تاريخ التعديل'),
        ], 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB');

        // Create an index for the parent_id column for faster lookups
        $this->createIndex(
            'idx-accounts-parent_id',
            $this->table,
            'parent_id'
        );

        // Add foreign key constraint for parent_id referencing the same table (self-referencing)
        $this->addForeignKey(
            'fk-accounts-parent_id',
            $this->table,
            'parent_id',
            $this->table,
            'id',
            'SET NULL',
            'CASCADE'
        );

        // Seed main accounts data
        $time = time();
        // حسابات رئيسية مثل:
        // الأصول، الخصوم، حقوق الملكية، الإيرادات، والمصروفات
        $this->batchInsert($this->table, 
            ['code', 'name', 'description', 'balance', 'is_active', 'parent_id', 'created_at', 'updated_at'], 
            [
                // الأصول
                ['1000', 'الأصول', 'حساب رئيسي للأصول', 0, 1, null, $time, $time],
                // الخصوم
                ['2000', 'الخصوم', 'حساب رئيسي للخصوم', 0, 1, null, $time, $time],
                // حقوق الملكية
                ['3000', 'حقوق الملكية', 'حساب رئيسي لحقوق الملكية', 0, 1, null, $time, $time],
                // الإيرادات
                ['4000', 'الإيرادات', 'حساب رئيسي للإيرادات', 0, 1, null, $time, $time],
                // المصروفات
                ['5000', 'المصروفات', 'حساب رئيسي للمصروفات', 0, 1, null, $time, $time],
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // Drop the foreign key for parent_id
        $this->dropForeignKey(
            'fk-accounts-parent_id',
            $this->table
        );

        // Drop the index for parent_id
        $this->dropIndex(
            'idx-accounts-parent_id',
            $this->table
        );

        // Drop the table
        $this->dropTable($this->table);
    }
}
