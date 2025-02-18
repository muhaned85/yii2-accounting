<?php

namespace muh\accounting\Services;

use muh\accounting\Models\Account;
use muh\accounting\Models\JournalEntryDetail;
use Yii;

/**
 * Service class for generating various financial reports.
 */
class ReportService
{
    /**
     * Generates the Trial Balance report.
     *
     * This report aggregates the total debit and credit amounts for each account,
     * and calculates the net balance.
     *
     * @return array List of accounts with their debit, credit, and net balance.
     */
    public function generateTrialBalance()
    {
        $accounts = Account::find()->all();
        $report = [];

        foreach ($accounts as $account) {
            // Aggregate the debit and credit amounts for the account
            $debit = (float) JournalEntryDetail::find()
                ->where(['account_id' => $account->id])
                ->sum('debit');

            $credit = (float) JournalEntryDetail::find()
                ->where(['account_id' => $account->id])
                ->sum('credit');

            // Net balance calculation (can be positive or negative)
            $balance = $debit - $credit;

            $report[] = [
                'account_id'   => $account->id,
                'account_code' => $account->code,
                'account_name' => $account->name,
                'debit'        => $debit,
                'credit'       => $credit,
                'balance'      => $balance,
            ];
        }

        return $report;
    }

    /**
     * Generates the Income Statement report.
     *
     * This report calculates the total revenues, total expenses, and net income.
     * For demonstration purposes, it assumes:
     * - Accounts with code starting with '4' represent revenues.
     * - Accounts with code starting with '5' represent expenses.
     *
     * @return array Income Statement data.
     */
    public function generateIncomeStatement()
    {
        // Sum revenues: assuming revenue accounts have codes starting with "4"
        $totalRevenue = (float) JournalEntryDetail::find()
            ->joinWith('account')
            ->andWhere(['like', 'account.code', '4%', false])
            ->sum('credit');

        // Sum expenses: assuming expense accounts have codes starting with "5"
        $totalExpense = (float) JournalEntryDetail::find()
            ->joinWith('account')
            ->andWhere(['like', 'account.code', '5%', false])
            ->sum('debit');

        // Calculate net income
        $netIncome = $totalRevenue - $totalExpense;

        return [
            'total_revenue' => $totalRevenue,
            'total_expense' => $totalExpense,
            'net_income'    => $netIncome,
        ];
    }

    /**
     * Generates the Balance Sheet report.
     *
     * This report aggregates assets, liabilities, and equity.
     * For demonstration, it assumes:
     * - Accounts with code starting with '1' represent assets.
     * - Accounts with code starting with '2' represent liabilities.
     * - Accounts with code starting with '3' represent equity.
     *
     * @return array Balance Sheet data.
     */
    public function generateBalanceSheet()
    {
        $assets = Account::find()->where(['like', 'code', '1%', false])->all();
        $liabilities = Account::find()->where(['like', 'code', '2%', false])->all();
        $equity = Account::find()->where(['like', 'code', '3%', false])->all();

        $calculateTotal = function ($accounts) {
            $total = 0;
            foreach ($accounts as $account) {
                $debit = (float) JournalEntryDetail::find()
                    ->where(['account_id' => $account->id])
                    ->sum('debit');

                $credit = (float) JournalEntryDetail::find()
                    ->where(['account_id' => $account->id])
                    ->sum('credit');

                // Net amount for the account
                $total += $debit - $credit;
            }
            return $total;
        };

        $totalAssets = $calculateTotal($assets);
        $totalLiabilities = $calculateTotal($liabilities);
        $totalEquity = $calculateTotal($equity);

        return [
            'total_assets'      => $totalAssets,
            'total_liabilities' => $totalLiabilities,
            'total_equity'      => $totalEquity,
        ];
    }

    /**
     * Generates a simple Cash Flow Statement.
     *
     * This report demonstrates cash flow based on a dedicated cash account.
     * For this example, it is assumed that the cash account has a specific code (e.g., '101').
     *
     * @return array Cash Flow data.
     */
    public function generateCashFlowStatement()
    {
        // Assume the cash account has the code '101'
        $cashAccount = Account::find()->where(['code' => '101'])->one();
        if (!$cashAccount) {
            return [
                'cash_flow' => 0,
                'message'   => 'Cash account not found.',
            ];
        }

        $totalDebit = (float) JournalEntryDetail::find()
            ->where(['account_id' => $cashAccount->id])
            ->sum('debit');

        $totalCredit = (float) JournalEntryDetail::find()
            ->where(['account_id' => $cashAccount->id])
            ->sum('credit');

        // Net cash flow calculation
        $cashFlow = $totalDebit - $totalCredit;

        return [
            'cash_account' => $cashAccount->name,
            'cash_flow'    => $cashFlow,
        ];
    }
}
