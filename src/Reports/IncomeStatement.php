<?php

namespace muh\accounting\Reports;

use muh\accounting\Models\JournalEntryDetail;
use yii\base\Component;

/**
 * Class IncomeStatement
 *
 * This class is responsible for generating the Income Statement report.
 *
 * It calculates:
 * - Total Revenue: Sum of credits from revenue accounts (assumed to have codes starting with '4')
 * - Total Expense: Sum of debits from expense accounts (assumed to have codes starting with '5')
 * - Net Income: The difference between total revenue and total expense.
 *
 * @package muh\accounting\Reports
 */
class IncomeStatement extends Component
{
    /**
     * Generates the Income Statement report.
     *
     * @return array An associative array containing:
     *   - total_revenue: Total revenue amount.
     *   - total_expense: Total expense amount.
     *   - net_income: Net income calculated as total revenue minus total expense.
     */
    public function generate(): array
    {
        // Calculate total revenue: Sum of credits for revenue accounts (code starts with '4')
        $totalRevenue = (float) JournalEntryDetail::find()
            ->joinWith('account')
            ->andWhere(['like', 'account.code', '4%', false])
            ->sum('credit');

        // Calculate total expense: Sum of debits for expense accounts (code starts with '5')
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
}
