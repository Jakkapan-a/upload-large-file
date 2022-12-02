<?php

namespace App\Http\Controllers;

use http\Client\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Pion\Laravel\ChunkUpload\Handler\HandlerFactory;
use Pion\Laravel\ChunkUpload\Receiver\FileReceiver;

class UploadControlller extends Controller
{
    public function uploadFile(Request $request)
    {
        $receiver = new FileReceiver('file', $request, HandlerFactory::classFromRequest($request));

        if($receiver->isUploaded() === false) {
            return response()->json(['success' => false, 'message' => 'File not uploaded']);
        }

        $save = $receiver->receive();
        if($save->isFinished()) {
//            dd($save->getFile()->getRealPath());
            $file = $save->getFile();
            $extension = $save->getFile()->getClientOriginalExtension();
            $filename = str_replace('.'.$extension, '', $save->getFile()->getClientOriginalName());
            $filename = str_replace(' ', '', $filename);
            $filename .='_'.md5(time()).'.'.$extension;

            $disk = Storage::disk('public');
            $path= $disk->putFileAs('videos',$file,$filename);
            unlink($file->getRealPath());
            return response()->json([
                'path' => asset($path),
                'filename' => $filename,
            ]);
        }

        $header = $save->handler();
        return response()->json([
            'done' => $header->getPercentageDone(),
            'status' => true,
        ]);
    }
}
