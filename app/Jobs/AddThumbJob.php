<?php

namespace App\Jobs;

use App\Http\Services\ImageUploadService;
use App\Models\Image;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class AddThumbJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private Image $image;

    private ImageUploadService $service;

    private string $size;

    /**
     * Create a new job instance.
     */
    public function __construct(Image $image, $size, ImageUploadService $service)
    {
        $this->image = $image;
        $this->service = $service;
        $this->size = $size;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {

        $this->service->setFolder($this->image->id);
        $this->service->generateThumbs(public_path('images/' . $this->image->id . '/' . $this->image->name));
        $sizes = $this->image->sizes;
        $sizes[] = "custom_" . $this->size;
        $sizes = array_unique($sizes);

        $this->image->update(['sizes' => $sizes]);
    }

}
