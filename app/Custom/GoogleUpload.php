<?php

namespace App\Custom;

use Google\Cloud\Storage\StorageClient;
use Illuminate\Support\Facades\Auth;

class GoogleUpload
{
    public function uploadGoogle($file)
    {
        $user = Auth::user();
        //get the credentials in the json file
        $googleConfigFile = file_get_contents(config_path('karyopay-google.json'));
        //create a StorageClient object
        $storage = new StorageClient([
            'keyFile' => json_decode($googleConfigFile, true)
        ]);

        //get the bucket name from the env file
        $storageBucketName = config('googlecloud.storage_bucket');
        //pass in the bucket name
        $bucket = $storage->bucket($storageBucketName);
        $image_path = $file->getRealPath();
        //rename the file
        $fileName = $user->name.'-'.time().'.'.$file->extension();

        //open the file using fopen
        $fileSource = fopen($image_path, 'r');
        //specify the path to the folder and sub-folder where needed
        $googleCloudStoragePath = 'product-files/' . $fileName;

        //upload the new file to google cloud storage
        $request = $bucket->upload($fileSource, [
            'predefinedAcl' => 'publicRead',
            'name' => $googleCloudStoragePath
        ]);

        if ($request){

            return [
                'done'=>true,
                'link'=>'https://storage.googleapis.com/karyopay-upload/product-files/'.$fileName
            ];
        }else{
            return [
                'done'=>false,
            ];
        }
    }
}
