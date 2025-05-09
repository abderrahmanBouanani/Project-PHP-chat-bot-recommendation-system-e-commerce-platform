<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MoveImages extends Command
{
    protected $signature = 'images:move';
    protected $description = 'Move images from storage to public directory';

    public function handle()
    {
        $source = storage_path('app/public/images');
        $destination = public_path('images/products');

        if (!File::exists($destination)) {
            File::makeDirectory($destination, 0755, true);
        }

        $files = File::files($source);
        foreach ($files as $file) {
            $filename = $file->getFilename();
            File::copy($file->getPathname(), $destination . '/' . $filename);
            $this->info("Copied: $filename");
        }

        $this->info('All images have been moved successfully!');
    }
} 