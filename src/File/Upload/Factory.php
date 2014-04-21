<?php
namespace qio\File\Upload {
    class Factory {
        /**
         * Default factory for uploaded files
         * @param string $field
         * @param array $input
         * @return \qio\File\Upload\File
         */
        public function getFile($field, array $input) {
            $file = new File;
            $file->setFieldName($field);
            $file->setOriginalName($input['name']);
            $file->setTemporaryName($input['tmp_name']);
            $file->setMimeType($input['type']);
            $file->setSize($input['size']);
            $file->setErrorCode($input['error']);
            
            return $file;
        }
    }
}