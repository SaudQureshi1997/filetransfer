<?php

namespace App\Utils;

class FileTransfer
{
    protected $directory = 'app/uploads/';

    public function serve(string $path)
    {
        $path = ltrim($path, '/');

        return storage_path(
            $this->directory . $path
        );
    }

    /**
     * touch the directories before saving file
     *
     * @param string $dir
     * @return void
     */
    protected function touch(string $dir)
    {
        $dir = ltrim($dir, '/');

        $dir = storage_path(
            dirname($this->directory . $dir)
        );

        if (!file_exists($dir)) {
            mkdir(
                $dir,
                0755,
                true
            );
        }
    }

    /**
     * clone the file from remote
     *
     * @param string $url
     * @param string $path
     * @return string
     */
    public function clone(string $url, string $path)
    {
        $path = ltrim($path, '/');
        $this->touch($path);

        $source = fopen($url, 'rb');
        $destination = fopen(storage_path($this->directory . $path), 'wb');

        stream_copy_to_stream($source, $destination);

        fclose($source);
        fclose($destination);


        return $path;
    }

    public function remove(string $path)
    {
        $path = ltrim($path, '/');

        if (file_exists($path)) {
            unlink($path);
        }

        return $path;
    }
}
