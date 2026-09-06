<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ClassroomResource;
use Uploadcare\Api;
use Illuminate\Support\Facades\Log;

class ClassroomResourceController extends Controller
{
    /**
     * Upload a file to Uploadcare and persist its metadata to the database.
     */
    // 1. STORE METHOD (Save with folder path)
    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:jpeg,png,jpg,gif,svg,pdf,txt,css,js,html|max:10240',
            'classUuid' => 'required|string',
            'folderPath' => 'nullable|string', // e.g., "programming"
        ]);

        // Format folder path cleanly: trim slashes (e.g. "programming")
        $folderPath = trim($request->input('folderPath', '/'), '/');
        if (empty($folderPath)) {
            $folderPath = '/';
        }

        $uploadedFile = $request->file('file');

        $api = Api::create(config('services.uploadcare.public'), config('services.uploadcare.secret'));
        $uploadcareFile = $api->uploader()->fromContent(
            file_get_contents($uploadedFile->getRealPath()),
            $uploadedFile->getClientOriginalName(),
            $uploadedFile->getMimeType()
        );
        $uploadcareFile->store();

        $uuid = $uploadcareFile->getUuid();
        $fileUrl = $uploadcareFile->getOriginalFileUrl();

        $resource = ClassroomResource::create([
            'classroom_uuid'  => $request->classUuid,
            'user_id'         => auth()->id(),
            'file_name'       => $uploadedFile->getClientOriginalName(),
            'folder_path'     => $folderPath,
            'uploadcare_uuid' => $uuid,
            'file_url'        => $fileUrl,
            'mime_type'       => $uploadedFile->getMimeType(),
        ]);

        return response()->json([
            'success'  => true,
            'resource' => $resource
        ], 201);
    }

    // 2. INDEX METHOD (Return distinct folders and files for a directory level)
    public function index(Request $request, $classUuid)
    {
        // The current folder being browsed, default to root "/"
        $currentFolder = trim($request->query('folder', '/'), '/');
        if (empty($currentFolder)) {
            $currentFolder = '/';
        }

        // Fetch resources directly inside this folder
        $files = ClassroomResource::where('classroom_uuid', $classUuid)
            ->where('folder_path', $currentFolder)
            ->latest()
            ->get();

        // Find subfolders inside this current folder
        $subfolderPrefix = ($currentFolder === '/') ? '' : $currentFolder . '/';
        
        $allPaths = ClassroomResource::where('classroom_uuid', $classUuid)
            ->where('folder_path', 'LIKE', ($currentFolder === '/' ? '%' : $currentFolder . '/%'))
            ->pluck('folder_path')
            ->unique();

        $folders = [];
        foreach ($allPaths as $path) {
            if ($path === $currentFolder) continue;
            
            // Extract the next immediate subfolder level
            $relative = str_replace($subfolderPrefix, '', $path);
            $parts = explode('/', $relative);
            if (!empty($parts[0]) && !in_array($parts[0], $folders)) {
                $folders[] = $parts[0];
            }
        }

        return response()->json([
            'success'       => true,
            'currentFolder' => $currentFolder,
            'folders'       => $folders, // Subdirectory names (e.g. ["programming", "assets"])
            'files'         => $files,   // Actual file objects in this folder
        ]);
    }
}