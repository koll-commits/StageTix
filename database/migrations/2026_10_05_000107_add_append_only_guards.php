<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /** @var list<string> */
    private array $tables = ['payment_events', 'ledger_entries', 'audit_logs'];

    public function up(): void
    {
        foreach ($this->tables as $table) {
            DB::unprepared(
                "CREATE TRIGGER trg_{$table}_no_update BEFORE UPDATE ON {$table} FOR EACH ROW ".
                "SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = '{$table} bersifat append-only (UPDATE ditolak)'"
            );
            DB::unprepared(
                "CREATE TRIGGER trg_{$table}_no_delete BEFORE DELETE ON {$table} FOR EACH ROW ".
                "SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = '{$table} bersifat append-only (DELETE ditolak)'"
            );
        }
    }

    public function down(): void
    {
        foreach ($this->tables as $table) {
            DB::unprepared("DROP TRIGGER IF EXISTS trg_{$table}_no_update");
            DB::unprepared("DROP TRIGGER IF EXISTS trg_{$table}_no_delete");
        }
    }
};
