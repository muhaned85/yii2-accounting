<?php

namespace muh\accounting\Services;

use muh\accounting\Models\JournalEntry;
use muh\accounting\Models\JournalEntryDetail;
use muh\accounting\Exceptions\UnbalancedEntryException;
use Yii;

/**
 * Service class for handling journal entries operations such as creating entries,
 * ensuring they are balanced, and reversing entries.
 */
class JournalService
{
    /**
     * Creates a new journal entry with multiple details.
     *
     * Each detail should be an associative array containing:
     * - 'account_id': The ID of the account.
     * - 'debit': The debit amount (numeric).
     * - 'credit': The credit amount (numeric).
     *
     * @param string $entryDate   Date of the journal entry (format: YYYY-MM-DD)
     * @param string $description Description of the journal entry
     * @param array  $details     Array of journal entry details
     *
     * @return JournalEntry The created journal entry
     * @throws UnbalancedEntryException If the total debits do not equal the total credits
     * @throws \Exception               If saving any record fails
     */
    public function createEntry($entryDate, $description, array $details)
    {
        // Calculate total debit and credit amounts
        $totalDebit  = 0;
        $totalCredit = 0;

        foreach ($details as $detail) {
            $totalDebit  += isset($detail['debit']) ? (float)$detail['debit'] : 0;
            $totalCredit += isset($detail['credit']) ? (float)$detail['credit'] : 0;
        }

        // Check if the entry is balanced
        if ($totalDebit !== $totalCredit) {
            throw new UnbalancedEntryException('Total debit (' . $totalDebit . ') does not equal total credit (' . $totalCredit . ').');
        }

        // Start transaction to ensure data integrity
        $transaction = Yii::$app->db->beginTransaction();
        try {
            // Create the journal entry header
            $journalEntry = new JournalEntry();
            $journalEntry->entry_date  = $entryDate;
            $journalEntry->description = $description;
            // By default, is_reversed is 0 (not reversed)
            if (!$journalEntry->save()) {
                throw new \Exception('Failed to save journal entry: ' . implode(', ', $journalEntry->getFirstErrors()));
            }

            // Create each journal entry detail
            foreach ($details as $detailData) {
                $journalDetail = new JournalEntryDetail();
                $journalDetail->journal_entry_id = $journalEntry->id;
                $journalDetail->account_id       = $detailData['account_id'];
                $journalDetail->debit            = $detailData['debit'];
                $journalDetail->credit           = $detailData['credit'];
                if (!$journalDetail->save()) {
                    throw new \Exception('Failed to save journal entry detail: ' . implode(', ', $journalDetail->getFirstErrors()));
                }
            }

            $transaction->commit();
            return $journalEntry;
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        }
    }

    /**
     * Reverses an existing journal entry.
     *
     * This method creates a reversal journal entry where the debit and credit amounts
     * are swapped for each detail. It also marks the original journal entry as reversed.
     *
     * @param int $journalEntryId The ID of the journal entry to reverse
     *
     * @return JournalEntry The created reversal journal entry
     * @throws \Exception If the journal entry is not found, already reversed, or if any operation fails
     */
    public function reverseEntry($journalEntryId)
    {
        $journalEntry = JournalEntry::findOne($journalEntryId);
        if (!$journalEntry) {
            throw new \Exception('Journal entry not found.');
        }

        if ($journalEntry->is_reversed) {
            throw new \Exception('Journal entry is already reversed.');
        }

        $transaction = Yii::$app->db->beginTransaction();
        try {
            // Create reversal journal entry header
            $reversalEntry = new JournalEntry();
            $reversalEntry->entry_date  = date('Y-m-d');
            $reversalEntry->description = 'Reversal of entry ID ' . $journalEntry->id;
            // Mark reversal entry as reversed (or you can choose to use a separate flag if needed)
            $reversalEntry->is_reversed = 1;
            if (!$reversalEntry->save()) {
                throw new \Exception('Failed to create reversal journal entry: ' . implode(', ', $reversalEntry->getFirstErrors()));
            }

            // For each detail of the original entry, create a corresponding reversal detail
            foreach ($journalEntry->details as $detail) {
                $reversalDetail = new JournalEntryDetail();
                $reversalDetail->journal_entry_id = $reversalEntry->id;
                $reversalDetail->account_id       = $detail->account_id;
                // Swap debit and credit amounts
                $reversalDetail->debit = $detail->credit;
                $reversalDetail->credit = $detail->debit;
                if (!$reversalDetail->save()) {
                    throw new \Exception('Failed to create reversal journal entry detail: ' . implode(', ', $reversalDetail->getFirstErrors()));
                }
            }

            // Mark the original journal entry as reversed
            $journalEntry->is_reversed = 1;
            if (!$journalEntry->save()) {
                throw new \Exception('Failed to mark original journal entry as reversed: ' . implode(', ', $journalEntry->getFirstErrors()));
            }

            $transaction->commit();
            return $reversalEntry;
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        }
    }

    /**
     * Creates a new journal entry that supports multiple debit accounts and multiple credit accounts.
     *
     * This method accepts two separate arrays: one for debit details and one for credit details.
     * Each element in the arrays should be an associative array with the following keys:
     * - 'account_id': The ID of the account.
     * - 'amount': The monetary amount for that account.
     *
     * The method will:
     * - Sum the total debits and credits.
     * - Ensure that the totals are equal (i.e., the entry is balanced).
     * - Create the journal entry header.
     * - Create individual journal entry details for each debit (with credit = 0) and for each credit (with debit = 0).
     *
     * @param string $entryDate     Date of the journal entry (format: YYYY-MM-DD)
     * @param string $description   Description of the journal entry
     * @param array  $debitDetails  Array of debit details (each with 'account_id' and 'amount')
     * @param array  $creditDetails Array of credit details (each with 'account_id' and 'amount')
     *
     * @return JournalEntry The created journal entry
     *
     * @throws UnbalancedEntryException If the total debits do not equal the total credits.
     * @throws \Exception               If saving any record fails.
     */
    public function createMultiAccountEntry($entryDate, $description, array $debitDetails, array $creditDetails)
    {
        $totalDebit = 0;
        $totalCredit = 0;

        // Calculate total debit from the debit details array
        foreach ($debitDetails as $detail) {
            $totalDebit += isset($detail['amount']) ? (float)$detail['amount'] : 0;
        }

        // Calculate total credit from the credit details array
        foreach ($creditDetails as $detail) {
            $totalCredit += isset($detail['amount']) ? (float)$detail['amount'] : 0;
        }

        // Check if the entry is balanced
        if ($totalDebit !== $totalCredit) {
            throw new UnbalancedEntryException("Debits ($totalDebit) and credits ($totalCredit) do not match.");
        }

        // Start a transaction to ensure data integrity
        $transaction = Yii::$app->db->beginTransaction();
        try {
            // Create the journal entry header
            $journalEntry = new JournalEntry();
            $journalEntry->entry_date  = $entryDate;
            $journalEntry->description = $description;
            if (!$journalEntry->save()) {
                throw new \Exception('Failed to save journal entry: ' . implode(', ', $journalEntry->getFirstErrors()));
            }

            // Create journal entry details for each debit detail
            foreach ($debitDetails as $debitDetail) {
                $journalDetail = new JournalEntryDetail();
                $journalDetail->journal_entry_id = $journalEntry->id;
                $journalDetail->account_id       = $debitDetail['account_id'];
                $journalDetail->debit            = $debitDetail['amount'];
                $journalDetail->credit           = 0;
                if (!$journalDetail->save()) {
                    throw new \Exception('Failed to save debit detail: ' . implode(', ', $journalDetail->getFirstErrors()));
                }
            }

            // Create journal entry details for each credit detail
            foreach ($creditDetails as $creditDetail) {
                $journalDetail = new JournalEntryDetail();
                $journalDetail->journal_entry_id = $journalEntry->id;
                $journalDetail->account_id       = $creditDetail['account_id'];
                $journalDetail->debit            = 0;
                $journalDetail->credit           = $creditDetail['amount'];
                if (!$journalDetail->save()) {
                    throw new \Exception('Failed to save credit detail: ' . implode(', ', $journalDetail->getFirstErrors()));
                }
            }

            $transaction->commit();
            return $journalEntry;
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        }
    }
}
