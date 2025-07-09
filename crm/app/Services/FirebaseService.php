<?php

namespace App\Services;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Storage;

class FirebaseService
{
    protected $storage;
    protected $bucket;

    public function __construct()
    {
        $factory = (new Factory)->withServiceAccount(config('firebase.credentials'));

        $this->storage = $factory->createStorage();
        $this->bucket = $this->storage->getBucket(config('firebase.storage_bucket'));
    }

    public function uploadFile($file, $filename)
    {
        // Upload file contents to Firebase Storage
        $this->bucket->upload(
            file_get_contents($file),
            [
                'name' => $filename,
                'predefinedAcl' => 'publicRead' // 🔓 Make file publicly accessible
            ]
        );

        return $this->getPublicUrl($filename);
    }

    public function getPublicUrl($filename)
    {
        return "https://storage.googleapis.com/" . config('firebase.storage_bucket') . "/" . $filename;
    }
}
