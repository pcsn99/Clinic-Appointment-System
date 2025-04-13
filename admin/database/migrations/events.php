<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    public function up(): void
    {
        
        DB::statement("SET GLOBAL event_scheduler = ON");

        
        DB::statement("
            CREATE EVENT IF NOT EXISTS regenerate_attendance_pin
            ON SCHEDULE EVERY 1 HOUR
            DO
            UPDATE pin_codes
            SET pin_code = LPAD(FLOOR(RAND() * 999999), 6, '0'),
                updated_at = NOW()
            WHERE purpose = 'appointment_attendance' AND type = 'hourly';
        ");

        //
        DB::statement("
            CREATE EVENT IF NOT EXISTS regenerate_override_pin
            ON SCHEDULE EVERY 1 HOUR
            DO
            UPDATE pin_codes
            SET pin_code = LPAD(FLOOR(RAND() * 999999), 6, '0'),
                updated_at = NOW()
            WHERE purpose = 'slot_limit_override' AND type = 'hourly';
        ");
    }

    public function down(): void
    {
        DB::statement("DROP EVENT IF EXISTS regenerate_attendance_pin");
        DB::statement("DROP EVENT IF EXISTS regenerate_override_pin");
    }
};
