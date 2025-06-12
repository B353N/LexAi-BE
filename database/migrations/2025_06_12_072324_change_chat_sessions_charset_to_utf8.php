<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement('ALTER TABLE `chat_sessions` MODIFY `title` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL');
        DB::statement('ALTER TABLE `chat_messages` MODIFY `message` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reverting requires knowing the original charset, which is complex.
        // For this fix, we'll assume reverting to a common latin1 is sufficient if needed.
        DB::statement('ALTER TABLE `chat_sessions` MODIFY `title` VARCHAR(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL');
        DB::statement('ALTER TABLE `chat_messages` MODIFY `message` TEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL');
    }
};
