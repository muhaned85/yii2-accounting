<?php

namespace muh\accounting\Reports;

use muh\accounting\Models\Account;
use yii\base\Component;

/**
 * Class ChartOfAccounts
 *
 * This report class generates a tree structure for the chart of accounts.
 *
 * It assumes that the `accounts` table contains a `parent_id` field
 * which establishes a parent-child relationship between accounts.
 *
 * @package muh\accounting\Reports
 */
class ChartOfAccounts extends Component
{
    /**
     * Generates the Chart of Accounts tree.
     *
     * @return array The tree structure of accounts.
     */
    public function generate(): array
    {
        // Fetch all accounts ordered by code (or any preferred criteria)
        $accounts = Account::find()->orderBy('code')->all();

        // Convert accounts to a flat array with necessary fields
        $accountsArray = [];
        foreach ($accounts as $account) {
            $accountsArray[] = [
                'id'          => $account->id,
                'code'        => $account->code,
                'name'        => $account->name,
                'parent_id'   => $account->parent_id, // This field must exist in the accounts table
                'description' => $account->description,
                'balance'     => $account->balance,
            ];
        }

        // Build and return the tree structure from the flat accounts array
        return $this->buildTree($accountsArray);
    }

    /**
     * Recursively builds a tree structure from a flat array of accounts.
     *
     * @param array $accounts The flat array of accounts.
     * @param int|null $parentId The parent ID to filter by (null for root level).
     *
     * @return array The tree branch for the given parent.
     */
    protected function buildTree(array $accounts, $parentId = null): array
    {
        $branch = [];
        foreach ($accounts as $account) {
            if ($account['parent_id'] == $parentId) {
                $children = $this->buildTree($accounts, $account['id']);
                if ($children) {
                    $account['children'] = $children;
                } else {
                    $account['children'] = [];
                }
                $branch[] = $account;
            }
        }
        return $branch;
    }
}
