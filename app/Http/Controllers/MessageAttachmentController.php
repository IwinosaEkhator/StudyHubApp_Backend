<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\MessageAttachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MessageAttachmentController extends Controller
{
    // Download or view an attachment
    public function show($id)
    {
        $attach = MessageAttachment::findOrFail($id);
        return Storage::download($attach->path, $attach->name);
    }
}
