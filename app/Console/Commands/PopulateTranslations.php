<?php

namespace App\Console\Commands;

use App\Models\Translation;
use Illuminate\Console\Command;

class PopulateTranslations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'translations:populate';
    
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Populate the database with 100k+ translation records';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Translation::factory()->count(100000)->create();
        $this->info('100k+ translations have been populated.');
    }
}
