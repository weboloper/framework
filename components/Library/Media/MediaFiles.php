<?php

namespace Components\Library\Media;

class MediaFiles
{
    /**
     * @var \League\Flysystem\FilesystemInterface
     */
    private $fileSystem;

    const THUMBNAILS = [
        'small' => [150, 150],
        'medium'    => [300, 300],
        'large'     => [600, 600] 
    ];


    public function __construct()
    {
        $this->fileSystem = flysystem();
    }

    /**
     * Upload file to content/uploads
     * @param  string $localPath  upload path (/tmp/xxx)
     * @param  string $serverPath path to upload
     * @return boolean
     */
    public function uploadFile($localPath, $serverPath, $extension = null)
    {   // dosya adı bilerek hata versin bir daha yüklesin
        // if ($this->checkFileExists($serverPath)) {
        //     $item = explode($extension, $serverPath);
        //     $serverPath = $item[0] . uniqid() . '.' . $extension;
        // }
        $stream = fopen($localPath, 'r+');
        $status = $this->fileSystem->writeStream($serverPath, $stream);

        return $status;
    }

    /**
     * Looking given file is already on server or not
     * @param  String $serverPath
     * @return boolean
     */
    public function checkFileExists($serverPath)
    {
        return $this->fileSystem->has($serverPath);
    }
}
