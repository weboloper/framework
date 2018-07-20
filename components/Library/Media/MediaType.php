<?php

namespace Components\Library\Media;
 
class MediaType
{   
    //  Extension MIME Type
    // .doc      application/msword
    // .dot      application/msword

    // .docx     application/vnd.openxmlformats-officedocument.wordprocessingml.document
    // .dotx     application/vnd.openxmlformats-officedocument.wordprocessingml.template
    // .docm     application/vnd.ms-word.document.macroEnabled.12
    // .dotm     application/vnd.ms-word.template.macroEnabled.12

    // .xls      application/vnd.ms-excel
    // .xlt      application/vnd.ms-excel
    // .xla      application/vnd.ms-excel

    // .xlsx     application/vnd.openxmlformats-officedocument.spreadsheetml.sheet
    // .xltx     application/vnd.openxmlformats-officedocument.spreadsheetml.template
    // .xlsm     application/vnd.ms-excel.sheet.macroEnabled.12
    // .xltm     application/vnd.ms-excel.template.macroEnabled.12
    // .xlam     application/vnd.ms-excel.addin.macroEnabled.12
    // .xlsb     application/vnd.ms-excel.sheet.binary.macroEnabled.12

    // .ppt      application/vnd.ms-powerpoint
    // .pot      application/vnd.ms-powerpoint
    // .pps      application/vnd.ms-powerpoint
    // .ppa      application/vnd.ms-powerpoint

    // .pptx     application/vnd.openxmlformats-officedocument.presentationml.presentation
    // .potx     application/vnd.openxmlformats-officedocument.presentationml.template
    // .ppsx     application/vnd.openxmlformats-officedocument.presentationml.slideshow
    // .ppam     application/vnd.ms-powerpoint.addin.macroEnabled.12
    // .pptm     application/vnd.ms-powerpoint.presentation.macroEnabled.12
    // .potm     application/vnd.ms-powerpoint.template.macroEnabled.12
    // .ppsm     application/vnd.ms-powerpoint.slideshow.macroEnabled.12

    // .mdb      application/vnd.ms-access


    public $imageTypes = [
        'image/gif',
        'image/jpg',
        'image/png',
        'image/bmp',
        'image/jpeg'
    ];
    public $videoTypes = [
        'video/mp3',
        'video/mp4'
    ];

    public $documentTypes  = [
        'text/plain',
        'application/pdf',
        'application/zip',

        'application/vnd.ms-powerpoint',
        'application/vnd.ms-powerpoint',
        'application/vnd.ms-powerpoint',
        'application/vnd.ms-powerpoint',

        'application/msword',
        'application/msword',

        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.template',
        'application/vnd.ms-word.document.macroEnabled.12',
        'application/vnd.ms-word.template.macroEnabled.12',

        'application/vnd.openxmlformats-officedocument.presentationml.presentation',
        'application/vnd.openxmlformats-officedocument.presentationml.template',
        'application/vnd.openxmlformats-officedocument.presentationml.slideshow',
        'application/vnd.ms-powerpoint.addin.macroEnabled.12',
        'application/vnd.ms-powerpoint.presentation.macroEnabled.12',
        'application/vnd.ms-powerpoint.template.macroEnabled.12',
        'application/vnd.ms-powerpoint.slideshow.macroEnabled.12'
    ];
    /**
     * Attempt to determine the real file type of a file.
     *
     * @param string $extension Extension (eg 'jpg')
     *
     * @return boolean
     */
    public function imageCheck($extension)
    {
        $allowedTypes = $this->imageTypes;
        return in_array($extension, $allowedTypes);
    }

    /**
     * @param $extension
     * @return bool
     */
    public function videoCheck($extension)
    {
        $allowedTypes = $this->videoTypes;
        return in_array($extension, $allowedTypes);
    }

    /**
     * get file extension allowed for upload from db
     * @return array
     */
    public function getExtensionAllowed()
    {
        return array_merge($this->imageTypes, $this->videoTypes, $this->documentTypes);
    }

    public function checkExtension($extension)
    {
        $allowedTypes = $this->getExtensionAllowed();
        return in_array($extension, $allowedTypes);
    }
}
