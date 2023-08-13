<?php

namespace App\Http\Controllers\Api\Upload;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Plank\Mediable\Facades\MediaUploader;
use Plank\Mediable\Media;

class UploadController extends Controller
{
    public function upload(Request $request)
    {
        $this->authorize('upload_file');
        try {
            $files = $request->file('files');
            $uploadedFiles = [];

            foreach ($files as $file) {
                $uploaded_media = MediaUploader::fromSource($file)
                    ->setAllowedMimeTypes(['image/webp', 'image/png', 'image/jpg', 'image/jpeg'])
                    ->setAllowedExtensions(['jpg', 'jpeg', 'png', 'webp'])
                    ->toDestination('uploads', date('Y-m-d'))
                    ->upload();

                $uploadedFiles[] = [
                    'id' => $uploaded_media->id,
                    'url' => $uploaded_media->getUrl(),
                ];
            }
        } catch (Exception $e) {
            return response()->json([
                'status' => 'failed',
                'message' => 'media upload failed'.$e->getMessage(),
            ], 500);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'media uploaded successfully',
            'files' => $uploadedFiles,
        ], 200);
    }

    public function delete($id)
    {
        $this->authorize('delete_file');
        try {
            $id = explode(',', $id);
            media::destroy($id);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'failed',
                'message' => 'failed to delete media',

            ], 500);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'media deleted successfully',
        ], 200);
    }
}
