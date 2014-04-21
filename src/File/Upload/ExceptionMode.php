<?php
namespace qio\File\Upload {
    class ExceptionMode extends \kenum\Enum\Base {
        const OK         = UPLOAD_ERR_OK;
        const INI_SIZE   = UPLOAD_ERR_INI_SIZE;
        const FORM_SIZE  = UPLOAD_ERR_FORM_SIZE;
        const PARTIAL    = UPLOAD_ERR_PARTIAL;
        const NO_FILE    = UPLOAD_ERR_NO_FILE;
        const NO_TMP_DIR = UPLOAD_ERR_NO_TMP_DIR;
        const CANT_WRITE = UPLOAD_ERR_CANT_WRITE;
        const EXTENSION  = UPLOAD_ERR_EXTENSION;
        
        /**
         * Helper function to translate error code into reasonable message
         * @return string
         */
        function __toString() {
            $msg = '';
            switch ($this->value()) {
                case self::OK:
                    $msg = 'The file was successfully uploaded';
                    break;
                case self::INI_SIZE:
                    $msg = 'The size exceeds upload_max_filesize set in php.ini';
                    break;
                case self::FORM_SIZE:
                    $msg = 'The size exceeds MAX_FILE_SIZE set in the HTML form';
                    break;
                case self::PARTIAL:
                    $msg = 'The file was only partially uploaded';
                    break;
                case self::NO_FILE:
                    $msg = 'No file was uploaded';
                    break;
                case self::NO_TMP_DIR:
                    $msg = 'No temporary directory was set';
                    break;
                case self::CANT_WRITE:
                    $msg = 'Can not write to disk';
                    break;
                case self::EXTENSION:
                    $msg = 'File upload stopped due to extension';
                    break;
            }

            return $msg;
        }
    }
}