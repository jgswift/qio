<?php
namespace qio\File\Upload {
    /**
     * This class serves mainly as a container for uploaded files and shouldn't be used otherwise
     */
    class File extends \qio\File
    {
        private $name;
        private $originalName;
        private $temporaryName;
        private $fieldName;
        private $mimeType;
        private $errorCode;
        private $uploaded = false;

        public function setName($name) {
            $this->name = $name;
        }

        public function getName() {
            return $this->name;
        }

        public function setOriginalName($name) {
            $this->originalName = $name;
        }

        public function getOriginalName() {
            return $this->originalName;
        }

        public function setTemporaryName($temporaryName) {
            $this->temporaryName = $temporaryName;
        }

        public function getTemporaryName() {
            return $this->temporaryName;
        }
        
        public function getPath() {
            return $this->getTemporaryName();
        }
        
        public function setPath($path) {
            $this->setTemporaryName($path);
        }

        public function setFieldName($field) {
            $this->fieldName = $field;
        }

        public function getFieldName() {
            return $this->fieldName;
        }

        public function setMimeType($type) {
            $this->mimeType = $type;
        }

        public function getMimeType() {
            return $this->mimeType;
        }

        public function setErrorCode($code) {
            $this->errorCode = new Upload\ExceptionMode();
            
            if(Upload\ExceptionMode::defined($code)) {
                $this->errorCode->value($code);
            } else {
                throw new \InvalidArgumentException(sprintf('The error code "%s" is invalid', $code));
            }
        }

        public function getErrorCode() {
            return $this->errorCode->value();
        }

        public function getErrorMessage() {
            return (string)$this->errorCode;
        }

        /**
         * Updates flag whether file completed upload procedure or not
         * @param boolean $uploaded
         * @return boolean
         */
        public function setUploaded($uploaded) {
            if(is_bool($uploaded)) {
                return $this->uploaded = $uploaded;
            }
            
            return false;
        }
        
        /**
         * Checks if file is finished uploading and accounted for
         * @return boolean
         */
        public function isUploaded() {
            return $this->uploaded;
        }

        /**
         * Extended pathinfo
         * @return array
         */
        function getPathInfo() {
            if(is_null($this->path)) {
                $path = $this->originalName;
            } else {
                $path = $this->path;
            }

            return pathinfo($path);
        }
        
        /**
         * Alias for is_uploaded_file
         * @return boolean
         */
        public function valid() {
            return is_uploaded_file($this->getTemporaryName());
        }
    }
}

