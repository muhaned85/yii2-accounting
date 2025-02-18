<?php

namespace muh\accounting\Reports;

use muh\accounting\Models\Account;
use muh\accounting\Models\JournalEntryDetail;
use yii\base\Component;

/**
 * Class TrialBalance
 *
 * This class is responsible for generating the Trial Balance report.
 *
 * It aggregates for each account:
 * - Total debit amount.
 * - Total credit amount.
 * - Net balance calculated as (total debit - total credit).
 *
 * @package muh\accounting\Reports
 */
class TrialBalance extends Component
{
    /**
     * Generates the Trial Balance report.
     *
     * @return array An array of accounts with their respective financial data:
     *   - account_id: The account identifier.
     *   - account_code: The account code.
     *   - account_name: The account name.
     *   - total_debit: Total debit amount for the account.
     *   - total_credit: Total credit amount for the account.
     *   - net_balance: Net balance (total_debit - total_credit).
     */
    public function generate(): array
    {
        $accounts = Account::find()->all();
        $trialBalance = [];

        foreach ($accounts as $account) {
            // Calculate total debit for the account.
            $totalDebit = (float) JournalEntryDetail::find()
                ->where(['account_id' => $account->id])
                ->sum('debit');

            // Calculate total credit for the account.
            $totalCredit = (float) JournalEntryDetail::find()
                ->where(['account_id' => $account->id])
                ->sum('credit');

            // Append the account data to the trial balance array.
            $trialBalance[] = [
                'account_id'   => $account->id,
                'account_code' => $account->code,
                'account_name' => $account->name,
                'total_debit'  => $totalDebit,
                'total_credit' => $totalCredit,
                'net_balance'  => $totalDebit - $totalCredit,
            ];
        }

        return $trialBalance;
    }
}
