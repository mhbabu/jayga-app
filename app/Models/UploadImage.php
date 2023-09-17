<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class UploadImage extends Model
{
    use HasFactory;

    protected $table = 'images';
    protected $fillable = ['user_id', 'vendor_id', 'created_by', 'updated_by'];

    /**
     * Get all of the User for the UploadImage
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }


    /**
     * Get all of the ImageDetail for the UploadImage
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function imageDetails(): HasMany
    {
        return $this->hasMany(ImageDetail::class, 'image_id', 'id');
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
