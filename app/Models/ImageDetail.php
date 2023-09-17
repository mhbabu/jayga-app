<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Intervention\Image\Facades\Image;

class ImageDetail extends Model
{
    use HasFactory;

    protected $table = 'image_details';
    protected $fillable = ['image_id', 'image', 'image_path', 'created_by', 'updated_by'];


    public static function StorePhoto($referenceType,$referenceId,$file,$dimensions=[300,300]){

        $directory = 'uploads';
        switch ($referenceType) {
            case 'image':
                $directory .= '/image/';
                break;

            default:
                $directory .= '/image/';
        }
        if($file){
            $fileName = trim(str_replace('.','',uniqid($referenceType."_",true)).'.'.$file->getClientOriginalExtension());

            if(!file_exists($directory)){
                mkdir($directory,0777,true);
                $indexFile = fopen($directory . "/index.html", "w");
                fclose($indexFile);
            }

            // image Width x Height | $dimensions[0] x $dimensions[1]
            $path = $directory.$fileName;
            Image::make($file->getRealPath())->resize($dimensions[0], $dimensions[1], function($constraint){
                $constraint->aspectRatio();
            })->save($path, 100); // quality medium

            $imageDetail = new ImageDetail();
            $imageDetail->image_id        = $referenceId;
            $imageDetail->reference_type  = $referenceType;
            $imageDetail->image           = $fileName;
            $imageDetail->image_path      = $directory.$fileName;
            $imageDetail->dimensions      = "$dimensions[0] x $dimensions[1]";
            $imageDetail->save();

            return $fileName;
        }

        return false;
    }

    public static function boot() {
        parent::boot();
        static::creating(function($user) {
           if(auth()->user()){
               $user->created_by = auth()->user()->id ?? null;
               $user->updated_by = auth()->user()->id ?? null;
           }
        });

        static::updating(function($user) {
           if(auth()->user())
               $user->updated_by = auth()->user()->id ?? null;
        });
    }
}
