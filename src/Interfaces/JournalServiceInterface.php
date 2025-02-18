<?php

namespace muh\accounting\Interfaces;

use muh\accounting\Models\JournalEntry;

/**
 * Interface JournalServiceInterface
 *
 * This interface defines the contract for journal entry operations,
 * including creating a new journal entry and reversing an existing one.
 */
interface JournalServiceInterface
{
    /**
     * Creates a new journal entry with multiple details.
     *
     * Each detail should be an associative array containing:
     * - 'account_id': The ID of the account.
     * - 'debit': The debit amount.
     * - 'credit': The credit amount.
     *
     * @param string $entryDate   Date of the journal entry (format: YYYY-MM-DD)
     * @param string $description Description of the journal entry
     * @param array  $details     Array of journal entry details
     *
     * @return JournalEntry The created journal entry
     *
     * @throws \Exception If saving the journal entry or its details fails,
     *                    or if the entry is unbalanced.
     */
    public function createEntry(string $entryDate, string $description, array $details): JournalEntry;

    /**
     * Reverses an existing journal entry.
     *
     * This method creates a reversal journal entry by swapping the debit
     * and credit amounts of each detail in the original entry, and marks
     * the original entry as reversed.
     *
     * @param int $journalEntryId The ID of the journal entry to reverse.
     *
     * @return JournalEntry The created reversal journal entry.
     *
     * @throws \Exception If the journal entry is not found, is already reversed,
     *                    or if the reversal process fails.
     */
    public function reverseEntry(int $journalEntryId): JournalEntry;
}
