<?php

namespace App\Traits;

trait UploadTrait{

    public function uploadImageAsBase64($base64,$extension)
    {

        $path = '';
        $folderPath = env('STORAGE_PATH') . $extension . '/';
        $image_base64 = base64_decode($base64);
        $path = uniqid() . '.jpg';
        $file = $folderPath . $path;
        file_put_contents($file, $image_base64);
        return 'storage/' . $extension . '/' . $path;
    }

}
