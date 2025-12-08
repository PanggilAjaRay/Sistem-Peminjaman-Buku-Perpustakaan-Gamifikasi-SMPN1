<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Fine;

class FixFineDescriptions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fines:fix-descriptions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix fine descriptions by rounding decimal values to whole numbers';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Fixing fine descriptions...');
        
        $fines = Fine::whereNotNull('description')->get();
        $updated = 0;
        
        foreach ($fines as $fine) {
            $description = $fine->description;
            $newDescription = $description;
            
            // Pattern 1: Fix "Keterlambatan X.XXX hari" - round days
            $newDescription = preg_replace_callback(
                '/Keterlambatan (\d+(?:\.\d+)?)\s+hari/',
                function ($matches) {
                    return 'Keterlambatan ' . round(floatval($matches[1])) . ' hari';
                },
                $newDescription
            );
            
            // Pattern 2: Fix "Pengurangan X.XXX point" - round points
            $newDescription = preg_replace_callback(
                '/Pengurangan (\d+(?:\.\d+)?)\s+point/',
                function ($matches) {
                    return 'Pengurangan ' . round(floatval($matches[1])) . ' point';
                },
                $newDescription
            );
            
            // Pattern 3: Fix "Denda Rp X.XXX" - keep as is (it's already formatted)
            // No changes needed for this pattern
            
            if ($newDescription !== $description) {
                $fine->description = $newDescription;
                $fine->save();
                $updated++;
                
                $this->line("Updated: {$description}");
                $this->line("     -> {$newDescription}");
            }
        }
        
        $this->info("Finished! Updated {$updated} fine descriptions.");
        
        return 0;
    }
}
