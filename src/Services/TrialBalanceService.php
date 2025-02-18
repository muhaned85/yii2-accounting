<?php

namespace muh\accounting\Services;

use muh\accounting\Models\Account;
use muh\accounting\Models\JournalEntryDetail;

/**
 * خدمة لإنشاء تقرير ميزان المراجعة.
 *
 * تقوم هذه الخدمة بتجميع الحسابات مع حساب إجمالي المبالغ المدينة والدائنة 
 * وحساب الرصيد الصافي لكل حساب.
 */
class TrialBalanceService
{
    /**
     * يُنشئ تقرير ميزان المراجعة.
     *
     * @return array قائمة الحسابات مع بيانات ميزان المراجعة (المعرف، الرمز، الاسم، إجمالي المدين، إجمالي الدائن، الرصيد)
     */
    public function generateTrialBalance()
    {
        $accounts = Account::find()->all();
        $trialBalance = [];

        foreach ($accounts as $account) {
            // حساب إجمالي المبالغ المدينة لكل حساب
            $totalDebit = (float) JournalEntryDetail::find()
                ->where(['account_id' => $account->id])
                ->sum('debit');

            // حساب إجمالي المبالغ الدائنة لكل حساب
            $totalCredit = (float) JournalEntryDetail::find()
                ->where(['account_id' => $account->id])
                ->sum('credit');

            // حساب الرصيد الصافي: المدين - الدائن
            $balance = $totalDebit - $totalCredit;

            $trialBalance[] = [
                'account_id'   => $account->id,
                'account_code' => $account->code,
                'account_name' => $account->name,
                'total_debit'  => $totalDebit,
                'total_credit' => $totalCredit,
                'balance'      => $balance,
            ];
        }

        return $trialBalance;
    }
}
