<?php

namespace App\Controllers\Http;

use App\Utils\FileTransfer;
use Validator;
use Request;

class FileTransferController
{
    protected $transfer;

    public function __construct()
    {
        $this->transfer = new FileTransfer();
    }

    public function show($path)
    {
        $file = $this->transfer->serve($path);
        // $info = pathinfo($file);
        // 'Content-Type', 'image/' . $info['extension']
        return response()->file($file);
    }

    public function store(Request $request)
    {
        $data = Validator::validate($request->all(), [
            'directory' => 'required|string',
            'tmpDir' => 'required|string',
            'name' => 'required|string'
        ]);

        $name = $data['name'];
        // trim slashes from tmpDir
        $tmpDir = trim($data['tmpDir'], '/');
        // get link for the file from client IP
        $url = "http://{$request->ip()}/$tmpDir/$name";

        // generate path from directory and name present in the request
        $path = trim($data['directory'], '/') . '/' . $name;

        // clone the file from client url to the local path
        $path = $this->transfer->clone($url, $path);

        return response()->json(['path' => $path], 200);
    }

    public function destroy(Request $request, Response $response)
    {
        $data = Validator::validate($request->request->all(), [
            'path' => 'required|string'
        ]);

        $path = $this->transfer->remove($data['path']);

        return response()->json(['path' => $path]);
    }
}
