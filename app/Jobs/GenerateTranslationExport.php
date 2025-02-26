<?php

// app/Jobs/GenerateTranslationExport.php

namespace App\Jobs;

use App\Models\Translation;
use App\Http\Resources\TranslationResource;
use App\Models\User;
use App\Notifications\ExportReadyNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Cache\Lock;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class GenerateTranslationExport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user;
    protected $lock;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function handle()
    {
         
        // Use a cache lock to prevent multiple exports
        $lock = Cache::lock('translation_export_lock_' . $this->user->id, 600); // Lock for 10 minutes

        if ($lock->get()) {
            try {
                $data = [];
                Translation::chunk(1000, function ($translations) use (&$data) {
                    foreach ($translations as $translation) {
                        $data[] = new TranslationResource($translation);
                    }
                });

                // Store the export in a file
                $fileName = 'translations_export_' . time() . '.json';
                Storage::put($fileName, json_encode($data));

                // Generate a download URL
                $downloadUrl = Storage::url($fileName);

                // Store the export in cache for 1 hour
                Cache::put('translations_export', $data, 3600);

                // Notify the user
                $user = User::find($this->user->id);
                $user->notify(new ExportReadyNotification($downloadUrl));
            } finally {
                // Release the lock
                $lock->release();
            }
        } else {
            // Another export is already in progress
            $user = User::find($this->user->id);
            $user->notify(new ExportReadyNotification(null, 'An export is already in progress. Please try again later.'));
        }
    }
}