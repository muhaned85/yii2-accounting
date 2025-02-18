<?php

namespace tests\models;

use Codeception\Test\Unit;
use muh\accounting\Models\Account;

/**
 * Class AccountTest
 *
 * This class contains unit tests for the Account model.
 *
 * @package tests\models
 */
class AccountTest extends Unit
{
    /**
     * This method is executed before each test.
     */
    protected function _before()
    {
        // Set up necessary preconditions, e.g. DB connection initialization if needed.
    }

    /**
     * This method is executed after each test.
     */
    protected function _after()
    {
        // Clean up after tests, e.g. remove test data from the database.
    }

    /**
     * Test creating and saving a new Account.
     */
    public function testCreateAccount()
    {
        $account = new Account();
        $account->code = 'TEST001';
        $account->name = 'Test Account';
        $account->description = 'This is a test account';
        $account->balance = 1000;
        $account->is_active = 1;
        $account->created_at = time();
        $account->updated_at = time();

        // Attempt to save the account and assert success.
        $this->assertTrue($account->save(), 'Account should be saved successfully');

        // Retrieve the account from the database and check values.
        $retrieved = Account::findOne($account->id);
        $this->assertNotNull($retrieved, 'Account should be retrievable from the database');
        $this->assertEquals('TEST001', $retrieved->code, 'The account code should match');
        $this->assertEquals('Test Account', $retrieved->name, 'The account name should match');
    }

    /**
     * Test that the account code must be unique.
     */
    public function testUniqueCodeValidation()
    {
        // Create the first account with a unique code.
        $account1 = new Account();
        $account1->code = 'DUPLICATE';
        $account1->name = 'Account 1';
        $account1->created_at = time();
        $account1->updated_at = time();
        $this->assertTrue($account1->save(), 'First account with unique code should be saved');

        // Create a second account with the same code.
        $account2 = new Account();
        $account2->code = 'DUPLICATE';
        $account2->name = 'Account 2';
        $account2->created_at = time();
        $account2->updated_at = time();
        
        // Validate the second account (should fail due to duplicate code).
        $this->assertFalse($account2->validate(), 'Second account with duplicate code should fail validation');
        $this->assertArrayHasKey('code', $account2->getErrors(), 'There should be an error for the code attribute');
    }
}
/*
run test
vendor/bin/codecept run tests/models
vendor/bin/phpunit

vendor\bin\codecept run tests/models
vendor\bin\codecept run

*/