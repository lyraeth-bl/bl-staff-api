<?php

namespace App\Console\Commands;

use App\Models\Attendance;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('attendance:mark-forgot-checkout')]
#[Description('Mark attendances that have not checked out by end of day.')]
class MarkForgotCheckout extends Command
{
    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $updated = Attendance::whereDate('date', today())
            // Sudah checkIn    
            ->whereNotNull('check_in')
            // Tapi belum checkOut
            ->whereNull('check_out')
            ->update(['status' => 'lupaCheckout']);

        $this->info("Marked {$updated} attendance(s) as lupaCheckout.");
    }
}
