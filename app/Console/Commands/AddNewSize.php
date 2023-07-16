<?php

namespace App\Console\Commands;

use App\Http\Services\ImageUploadService;
use App\Jobs\AddThumbJob;
use App\Models\Image;
use Illuminate\Console\Command;

class AddNewSize extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sizes:add {size}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create new size from all existing images';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $size = $this->argument('size');
        $images = Image::all();
        $imageUploadService = new ImageUploadService();
        $imageUploadService->setSizes($size);

        foreach($images as $image) {
            AddThumbJob::dispatch($image, $size, $imageUploadService);
        }
        $this->info('New size creation queued for all images');
    }
}
