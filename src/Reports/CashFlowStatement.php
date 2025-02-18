<?php

namespace muh\accounting\Reports;

use muh\accounting\Models\Account;
use muh\accounting\Models\JournalEntryDetail;
use yii\base\Component;

/**
 * Class CashFlowStatement
 *
 * This class is responsible for generating the Cash Flow Statement report.
 *
 * It calculates the net cash flow based on the cash account transactions.
 * By default, it assumes that the cash account has a specific code (e.g., '101').
 *
 * @package muh\accounting\Reports
 */
class CashFlowStatement extends Component
{
    /**
     * Generates the Cash Flow Statement report.
     *
     * @param string $cashAccountCode The code for the cash account (default: '101').
     *
     * @return array An associative array containing:
     *   - cash_account: Details of the cash account.
     *   - total_debit: Total debit amount for the cash account.
     *   - total_credit: Total credit amount for the cash account.
     *   - cash_flow: Net cash flow calculated as total_debit minus total_credit.
     *   - message: Optional message if the cash account is not found.
     */
    public function generate(string $cashAccountCode = '101'): array
    {
        // Find the cash account based on the provided code.
        $cashAccount = Account::find()->where(['code' => $cashAccountCode])->one();

        if (!$cashAccount) {
            return [
                'cash_account' => null,
                'total_debit'  => 0,
                'total_credit' => 0,
                'cash_flow'    => 0,
                'message'      => "Cash account with code '{$cashAccountCode}' not found.",
            ];
        }

        // Calculate total debit amount for the cash account
        $totalDebit = (float) JournalEntryDetail::find()
            ->where(['account_id' => $cashAccount->id])
            ->sum('debit');

        // Calculate total credit amount for the cash account
        $totalCredit = (float) JournalEntryDetail::find()
            ->where(['account_id' => $cashAccount->id])
            ->sum('credit');

        // Calculate net cash flow: total debit minus total credit
        $cashFlow = $totalDebit - $totalCredit;

        return [
            'cash_account' => [
                'id'   => $cashAccount->id,
                'code' => $cashAccount->code,
                'name' => $cashAccount->name,
            ],
            'total_debit'  => $totalDebit,
            'total_credit' => $totalCredit,
            'cash_flow'    => $cashFlow,
        ];
    }
}
