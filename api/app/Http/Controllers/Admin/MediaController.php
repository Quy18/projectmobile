<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class MediaController extends Controller
{
    /**
     * Hiển thị danh sách file media
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $directory = $request->input('directory', '');
        $directory = 'public/' . ltrim($directory, '/');
        
        $files = Storage::files($directory);
        $directories = Storage::directories($directory);
        
        $mediaFiles = [];
        foreach ($files as $file) {
            if (in_array(pathinfo($file, PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg', 'mp4', 'webm', 'mp3', 'pdf'])) {
                $mediaFiles[] = [
                    'name' => basename($file),
                    'path' => $file,
                    'url' => Storage::url($file),
                    'size' => Storage::size($file),
                    'last_modified' => Storage::lastModified($file),
                    'type' => pathinfo($file, PATHINFO_EXTENSION),
                ];
            }
        }
        
        $dirs = [];
        foreach ($directories as $dir) {
            $dirs[] = [
                'name' => basename($dir),
                'path' => $dir,
            ];
        }
        
        return response()->json([
            'files' => $mediaFiles,
            'directories' => $dirs,
            'current_directory' => $directory,
        ]);
    }

    /**
     * Upload file media
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function upload(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'files' => 'required|array',
            'files.*' => 'file|mimes:jpg,jpeg,png,gif,webp,svg,mp4,webm,mp3,pdf|max:10240',
            'directory' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $directory = 'public/' . ltrim($request->input('directory', ''), '/');
        $uploadedFiles = [];
        
        foreach ($request->file('files') as $file) {
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs($directory, $filename);
            
            $uploadedFiles[] = [
                'name' => $filename,
                'path' => $path,
                'url' => Storage::url($path),
                'size' => Storage::size($path),
                'last_modified' => Storage::lastModified($path),
                'type' => pathinfo($path, PATHINFO_EXTENSION),
            ];
        }
        
        return response()->json([
            'message' => 'Đã tải lên ' . count($uploadedFiles) . ' file thành công',
            'files' => $uploadedFiles,
        ]);
    }

    /**
     * Tạo thư mục
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function createDirectory(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'parent_directory' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $parentDirectory = 'public/' . ltrim($request->input('parent_directory', ''), '/');
        $newDirectory = $parentDirectory . '/' . $request->name;
        
        if (Storage::exists($newDirectory)) {
            return response()->json(['message' => 'Thư mục đã tồn tại'], 422);
        }
        
        Storage::makeDirectory($newDirectory);
        
        return response()->json([
            'message' => 'Đã tạo thư mục thành công',
            'directory' => [
                'name' => $request->name,
                'path' => $newDirectory,
            ],
        ]);
    }

    /**
     * Xóa file
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function deleteFile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'path' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $path = $request->path;
        
        if (!Storage::exists($path)) {
            return response()->json(['message' => 'File không tồn tại'], 404);
        }
        
        Storage::delete($path);
        
        return response()->json(['message' => 'Đã xóa file thành công']);
    }

    /**
     * Xóa thư mục
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function deleteDirectory(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'path' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $path = $request->path;
        
        if (!Storage::exists($path)) {
            return response()->json(['message' => 'Thư mục không tồn tại'], 404);
        }
        
        Storage::deleteDirectory($path);
        
        return response()->json(['message' => 'Đã xóa thư mục thành công']);
    }
} 