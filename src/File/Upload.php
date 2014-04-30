<?php
namespace qio\File {
    use observr, qio;
    
    class Upload {
        use observr\Subject;

        private $files = [];
        private $constraints;
        private $uploadDirectory;
        private $uploadFactory;
        private $uploadState;

        private $multiUpload = false;

        /**
         * Constructor for uploader utility
         * @param string $uploadDirectory
         * @param \qio\File\Upload\Factory $factory
         * @param \qio\File\Upload\State $state
         * @param array $constraints
         */
        function __construct($uploadDirectory = null, Upload\Factory $factory = null, Upload\State $state = null, array $constraints = []) {
            if(!is_null($uploadDirectory)) {
                $this->setUploadDirectory($uploadDirectory);
            }
            
            if(!is_null($factory)) {
                $this->uploadFactory = $factory;
            } else {
                $this->uploadFactory = new Upload\Factory;
            }
            
            if(!is_null($state)) {
                $this->uploadState = $state;
            } else {
                $this->uploadState = new Upload\State($this);
            }

            $this->constraints = array_merge($this->getDefaultConstraints(),$constraints);

            $this->parse();
        }
        
        /**
         * Retrieve default constraints, meant to be overridden
         * @return array
         */
        function getDefaultConstraints() {
            return [];
        }

        /**
         * Retrieves upload directory
         * @return \qio\Directory
         */
        function getUploadDirectory() {
            return $this->uploadDirectory;
        }

        /**
         * Update upload directory
         * @param qio\Directory|string $uploadDirectory
         * @throws qio\Exception
         */
        function setUploadDirectory($uploadDirectory)  {
            if(is_string($uploadDirectory)) {
                $uploadDirectory = new qio\Directory($uploadDirectory);
            }

            if(!$uploadDirectory->exists()) {
                throw new qio\Exception('Provided upload directory does not exist ("'.$uploadDirectory.'")');
            }

            if(!$uploadDirectory->isWritable()) {
                throw new qio\Exception('Provided upload directory is not writable ("'.$uploadDirectory.'")');
            }

            $this->uploadDirectory = $uploadDirectory;
        }
        
        /**
         * Helps prevent excessive unneeded calls to validate upload directory
         * especially when it is already known
         * @param qio\Directory $uploadDirectory
         */
        public function forceUploadDirectory($uploadDirectory) {
            if(is_string($uploadDirectory)) {
                $uploadDirectory = new qio\Directory($uploadDirectory);
            }
            
            $this->uploadDirectory = $uploadDirectory;
        }
 
        /**
         * Retrieve all uploaded files
         * @return array
         */
        function getFiles() {
            return array_values($this->files);
        }

        /**
         * Checks if any files were uploaded
         * @return boolean
         */
        function hasFiles() {
            return !empty($this->files);
        }

        /**
         * Retrieve specific file by name
         * @param string $name
         * @return \qio\File\Upload\File
         * @throws \BadMethodCallException
         * @throws \InvalidArgumentException
         */
        function getFile($name) {
            if($this->isMultipleUpload()) {
                throw new \BadMethodCallException('File cannot be found in multiple file upload');
            } elseif(!isset($this->files[$name])) {
                throw new \InvalidArgumentException('File does not exist');
            }

            return $this->files[$name];
        }

        /**
         * Checks if multiple files are present in upload
         * @return boolean
         */
        function isMultipleUpload() {
            return $this->multiUpload;
        }

        /**
         * Calculate total size of upload
         * @return integer
         */
        protected function size() {
            $total = 0;
            foreach($this->files as $file) {
                $total += $file->getSize();
            }

            return $total;
            
        }

        /**
         * Retrieve total upload size
         * @return type
         */
        function getSize() {
            return $this->size();
        }

        /**
         * Transform upload size into friendly string
         * @return string
         */
        function getSizeString() {
            $sizeView = new File\SizeView($this->getSize());
            return (string)$sizeView;
        }

        /**
         * Parse php upload stream
         */
        private function parse() {
            $input = \Kin::$input;

            foreach($input->files as $field => $fileInfo) {
                $this->multiUpload = \is_array($fileInfo['name']);
                        
                if($this->multiUpload) {
                    $this->build($field, $fileInfo);
                } else {
                    $this->files[$field] = $this->uploadFactory->getFile($field,$fileInfo);
                }
            }
        }
        
        /**
         * Build upload assets for every file using local factory
         * @param string $field
         * @param array $files
         */
        private function build($field, array $files) {
            $fileCount = count($files['name']);

            for($i = 0; $i < $fileCount; $i++) {
                $this->files[$field.$i] = $this->uploadFactory->getFile($field,[
                    'name'      => $files['name'][$i],
                    'tmp_name'  => $files['tmp_name'][$i],
                    'type'      => $files['type'][$i],
                    'size'      => $files['size'][$i],
                    'error'     => $files['error'][$i]
                ]);
            }
        }
        
        /**
         * Ensure every file was successfully uploaded to server
         * @return boolean
         */
        public function validate() {
            $files = $this->getFiles();
            
            foreach($files as $file) {
                if(!$file->valid()) {
                    return false;
                }
            }
            
            return true;
        }
        
        /**
         * Performs transfer
         * @param type $uploadTargetCallback
         */
        function save($uploadTargetCallback = null) {
            $this->uploadState->save($uploadTargetCallback);
        }

        /**
         * Retrieve only uploaded files
         * @return array
         */
        function getUploadedFiles() {
            $files = [];
            $allFiles = $this->getFiles();

            foreach($allFiles as $file) {
                if($file->isUploaded()) {
                    $files[] = $file;
                }
            }

            return $files;
        }

        /**
         * retrieves only not uploaded files
         * @return array
         */
        function getNotUploadedFiles() {
            return array_intersect($this->getFiles(), $this->getUploadedFiles());
        }
    }
}