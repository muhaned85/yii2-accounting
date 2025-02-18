<?php

namespace muh\accounting\Reports;

use muh\accounting\Models\CostCenter;
use muh\accounting\Models\Account;
use yii\base\Component;

/**
 * Class CostCenterReport
 *
 * This class generates a report for cost centers.
 * The report includes each cost center with its associated accounts and aggregated balances.
 */
class CostCenterReport extends Component
{
    /**
     * Generates the cost center report.
     *
     * For each cost center, it retrieves all linked accounts (based on the cost_center_id in accounts table),
     * and aggregates their balances.
     *
     * @return array The report data containing:
     *   - cost_center_id: The cost center ID.
     *   - code: Cost center code.
     *   - name: Cost center name.
     *   - description: Description of the cost center.
     *   - total_balance: The aggregated balance from all associated accounts.
     *   - accounts: A list of associated accounts (each with id, code, name, and balance).
     */
    public function generate(): array
    {
        $costCenters = CostCenter::find()->orderBy('code')->all();
        $report = [];

        foreach ($costCenters as $center) {
            // Retrieve accounts linked to this cost center
            $accounts = Account::find()->where(['cost_center_id' => $center->id])->all();
            $totalBalance = 0;
            $accountsData = [];

            foreach ($accounts as $account) {
                $accountsData[] = [
                    'id'      => $account->id,
                    'code'    => $account->code,
                    'name'    => $account->name,
                    'balance' => $account->balance,
                ];
                $totalBalance += $account->balance;
            }

            $report[] = [
                'cost_center_id' => $center->id,
                'code'           => $center->code,
                'name'           => $center->name,
                'description'    => $center->description,
                'total_balance'  => $totalBalance,
                'accounts'       => $accountsData,
            ];
        }

        return $report;
    }
}
