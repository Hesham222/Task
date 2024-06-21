<?php
namespace App\Http\Traits;
use App\Models\File;
trait FileTrait {

    public static function storeMultipleFiles($files, $pathFolder, $modelType, $modelId)
    {
        foreach ($files as $file)
        {
            $originalName = $file->getClientOriginalName();
            $size         = $file->getSize();
            $path         = $file->store($pathFolder, 'public');
            File::create([
                'name'        => $originalName,
                'size'        => $size,
                'path'        => $path,
                'model_type'  => $modelType,
                'model_id'    => $modelId,
            ]);
        }
        return 1;
    }

    public static function RemoveMultiFiles($model_type, $model_id)
    {
        $files  = File::where('model_id', $model_id)->where('model_type', $model_type)->get();
        foreach ($files as $file)
        {
            File::where('id', $file->id)->delete();
            self::RemoveSingleFile($file->path);
        }
    }

    public static function storeFile($file, $pathFolder, $modelType, $modelId)
    {
        $originalName = $file->getClientOriginalName();
        $size         = $file->getSize();
        $path         = $file->store($pathFolder, 'public');
        File::create([
            'name'        => $originalName,
            'size'        => $size,
            'path'        => $path,
            'model_type'  => $modelType,
            'model_id'    => $modelId,
        ]);
        return 1;
    }

    public static function RemoveFile($id)
    {
        $file  = File::find($id);
        self::RemoveSingleFile($file->path);
        $file->delete();
    }

    public static function storeSingleFile($file, $pathFolder)
    {
        if(!$file)
            return null;
        $path   = $file->store($pathFolder, 'public');
        return $path;
    }

    public static function RemoveSingleFile($file = null)
    {
        if(file_exists(public_path().DS().'storage'.DS().$file))
            unlink(public_path().DS().'storage'.DS().$file);
        else
            return 0;
    }


    public static function my_encrypt($data, $key) {
        $encryption_key = base64_decode($key);
        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
        $encrypted = openssl_encrypt($data, 'aes-256-cbc', $encryption_key, 0, $iv);
        return base64_encode($encrypted . '::' . $iv);
    }

    public static function my_decrypt($data, $key) {
        $encryption_key = base64_decode($key);
        list($encrypted_data, $iv) = explode('::', base64_decode($data), 2);
        return openssl_decrypt($encrypted_data, 'aes-256-cbc', $encryption_key, 0, $iv);
    }

}
