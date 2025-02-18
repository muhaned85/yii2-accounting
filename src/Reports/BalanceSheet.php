<?php

namespace muh\accounting\Reports;

use muh\accounting\Models\Account;
use muh\accounting\Models\JournalEntryDetail;
use yii\base\Component;

/**
 * Class BalanceSheet
 *
 * This class is responsible for generating the Balance Sheet report.
 *
 * It aggregates:
 * - Total Assets: Sum of net balances for asset accounts (assumed to have codes starting with '1').
 * - Total Liabilities: Sum of net balances for liability accounts (assumed to have codes starting with '2').
 * - Total Equity: Sum of net balances for equity accounts (assumed to have codes starting with '3').
 *
 * Net balance for each account is calculated as the total debits minus the total credits.
 *
 * @package muh\accounting\Reports
 */
class BalanceSheet extends Component
{
    /**
     * Generates the Balance Sheet report.
     *
     * @return array An associative array containing:
     *   - total_assets: Total net balance of asset accounts.
     *   - total_liabilities: Total net balance of liability accounts.
     *   - total_equity: Total net balance of equity accounts.
     */
    public function generate(): array
    {
        // Calculate total assets: sum net balances for accounts with codes starting with '1'
        $assets = Account::find()->where(['like', 'code', '1%', false])->all();
        $totalAssets = 0;
        foreach ($assets as $account) {
            $totalDebit = (float) JournalEntryDetail::find()
                ->where(['account_id' => $account->id])
                ->sum('debit');
            $totalCredit = (float) JournalEntryDetail::find()
                ->where(['account_id' => $account->id])
                ->sum('credit');
            $totalAssets += $totalDebit - $totalCredit;
        }

        // Calculate total liabilities: sum net balances for accounts with codes starting with '2'
        $liabilities = Account::find()->where(['like', 'code', '2%', false])->all();
        $totalLiabilities = 0;
        foreach ($liabilities as $account) {
            $totalDebit = (float) JournalEntryDetail::find()
                ->where(['account_id' => $account->id])
                ->sum('debit');
            $totalCredit = (float) JournalEntryDetail::find()
                ->where(['account_id' => $account->id])
                ->sum('credit');
            $totalLiabilities += $totalDebit - $totalCredit;
        }

        // Calculate total equity: sum net balances for accounts with codes starting with '3'
        $equity = Account::find()->where(['like', 'code', '3%', false])->all();
        $totalEquity = 0;
        foreach ($equity as $account) {
            $totalDebit = (float) JournalEntryDetail::find()
                ->where(['account_id' => $account->id])
                ->sum('debit');
            $totalCredit = (float) JournalEntryDetail::find()
                ->where(['account_id' => $account->id])
                ->sum('credit');
            $totalEquity += $totalDebit - $totalCredit;
        }

        return [
            'total_assets'      => $totalAssets,
            'total_liabilities' => $totalLiabilities,
            'total_equity'      => $totalEquity,
        ];
    }
}
