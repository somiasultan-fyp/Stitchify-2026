<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Tailor;
use Carbon\Carbon;

class ResetTailorSlots extends Command
{
    protected $signature = 'tailors:reset-slots';
    protected $description = 'Reset all tailors available slots to their base value monthly';

    public function handle()
    {
        $tailors = Tailor::where('status', 'approved')->get();
        $resetCount = 0;

        foreach ($tailors as $tailor) {
            $baseSlots = $tailor->base_max_slots ?? $tailor->max_slots;
            
            if ($tailor->available_slots < $baseSlots) {
                $tailor->available_slots = $baseSlots;
                $tailor->last_slot_reset_at = Carbon::now();
                $tailor->save();
                $resetCount++;
                
                $this->info("Reset slots for tailor: {$tailor->user->name} ({$baseSlots} slots)");
            }
        }

        $this->info("Total tailors reset: {$resetCount}");
        return Command::SUCCESS;
    }
}