<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class EnsureUploadDirectoriesExist extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'portal:ensure-upload-dirs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verify and create all 31 legacy upload subdirectories inside public/uploads/';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $directories = [
            'accounting',
            'album_images',
            'announcements',
            'asset_image',
            'attachments',
            'award',
            'chat_sound',
            'clients',
            'company',
            'corporate_benefits',
            'csv',
            'dbbackup',
            'document',
            'employee_verification',
            'employers',
            'expense',
            'files',
            'files_manager',
            'income_docs',
            'job_req_file',
            'languages_flag',
            'logo',
            'mail',
            'profile',
            'project',
            'resignations',
            'resume',
            'task',
            'ticket',
            'users',
            'wfhactivities',
        ];

        $basePath = public_path('uploads');

        if (!File::isDirectory($basePath)) {
            File::makeDirectory($basePath, 0755, true);
            $this->info("Created root upload directory: {$basePath}");
        }

        $createdCount = 0;
        foreach ($directories as $dir) {
            $path = $basePath . '/' . $dir;
            if (!File::isDirectory($path)) {
                File::makeDirectory($path, 0755, true);
                $createdCount++;
            }
        }

        $this->info("Verified all 31 upload subdirectories under public/uploads/ ({$createdCount} new created).");

        return Command::SUCCESS;
    }
}
