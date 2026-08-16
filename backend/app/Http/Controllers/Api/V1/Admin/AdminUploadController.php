<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreUploadRequest;

class AdminUploadController extends Controller
{
    public function store(StoreUploadRequest $request)
    {
        $folder = $request->validated('folder');
        $file = $request->file('file');
        $extension = $file->getClientOriginalExtension();

        if ($request->filled('filename')) {
            $path = $file->storeAs(
                $folder,
                $request->validated('filename') . '.' . $extension,
                'public'
            );
        } else {
            $path = $file->store($folder, 'public'); // random fallback
        }

        return response()->json([
            'path' => $path,
            'url' => url('storage/' . $path),
        ], 201);
    }
}
