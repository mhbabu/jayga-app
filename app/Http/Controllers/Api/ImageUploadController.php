<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ImageResource;
use App\Models\UploadImage;
use App\Models\ImageDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;

class ImageUploadController extends Controller
{
    protected $referenceType;
    protected $referenceId;
    protected $image;
    protected $dimensions;

    public function index()
    {
        $images = UploadImage::with('user','imageDetails')->get();
        return response(['data' => $images], Response::HTTP_OK);
    }

    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'user_id'     => 'required|exists:users,id',
            'vendor_id'   => 'required',
            'images'      => 'required|array',
            'images.*'    => 'required|image|mimes:jpeg,png,gif|max:2048',
        ], [], [
            'user_id' => 'user',
            'vendor_id' => 'vendor'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'error' => $validator->errors()
            ], 422);
        }

        $image = new UploadImage();
        $image->user_id = $request->input('user_id');
        $image->vendor_id = $request->input('vendor_id');
        $image->save();

        if ($request->hasFile('images')) {
            $this->referenceType = 'product';
            $this->referenceId = $image->id;
            $images = $request->file('images');
            $this->dimensions = [150, 180];
            foreach ($images as $this->image)
                ImageDetail::StorePhoto($this->referenceType, $this->referenceId, $this->image, $this->dimensions);
        }
        
        return response([
            'message' =>'Data saved successfully',
            'data'    => new ImageResource($image)
        ], Response::HTTP_CREATED);
    }
}
