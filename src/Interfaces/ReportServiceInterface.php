<?php

namespace muh\accounting\Interfaces;

/**
 * Interface ReportServiceInterface
 *
 * This interface defines the contract for generating various financial reports.
 */
interface ReportServiceInterface
{
    /**
     * Generates the Trial Balance report.
     *
     * @return array A list of accounts with their total debit, total credit, and net balance.
     */
    public function generateTrialBalance(): array;

    /**
     * Generates the Income Statement report.
     *
     * @return array Income Statement data including total revenues, total expenses, and net income.
     */
    public function generateIncomeStatement(): array;

    /**
     * Generates the Balance Sheet report.
     *
     * @return array Balance Sheet data including total assets, total liabilities, and total equity.
     */
    public function generateBalanceSheet(): array;

    /**
     * Generates the Cash Flow Statement report.
     *
     * @return array Cash Flow data including the cash account details and net cash flow.
     */
    public function generateCashFlowStatement(): array;
}
