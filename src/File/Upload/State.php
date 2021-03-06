<?php
namespace qio\File\Upload {
    use observr;
    
    class State {
        private $uploader;
        
        /**
         * Constructor for uploader utilty state
         * @param \qio\File\Upload $uploader
         */
        public function __construct(\qio\File\Upload $uploader) {
            $this->uploader = $uploader;
        }
        
        /**
         * Helper function to complete uploads
         * @param \qio\File\Upload\File $file
         * @param string $uploadDirectory
         * @return boolean
         */
        protected function saveFile(File $file, $uploadDirectory = null) {
            if($file->getErrorCode() !== ExceptionMode::OK) {
                return $this->error($file, $file->getErrorMessage());
            }

            $oldDirectory = $this->uploader->getUploadDirectory();

            if(!is_null($uploadDirectory)) {
                try {
                    $this->uploader->setUploadDirectory($uploadDirectory);
                } catch(\InvalidArgumentException $e) {
                    $this->error($file, $e->getMessage());
                } finally {
                    $this->uploader->forceUploadDirectory($oldDirectory);
                    
                    return false;
                }
            }

            $this->setState('constraint', $e = new observr\Event($this->uploader, ['file' => $file]));

            if($e->canceled) {
                $this->uploader->forceUploadDirectory($oldDirectory);
                
                return $this->error($file,'File upload constraint not valid');
            }

            $path = $this->uploader->getUploadDirectory() . $file->getFileName().'.'.$file->getExtension();

            $uploaded = $this->move($file->getTemporaryName(), $path);
            $this->uploader->forceUploadDirectory($oldDirectory);

            if($uploaded) {
                $file->setPath($path);
                $file->setUploaded(true);
                return true;
            } else {
                return $this->error($file,'Unable to move file');
            }
        }
        
        /**
         * Helper method deduplicating error handling
         * @param \qio\File\Upload\File $file
         * @param string $message
         * @return boolean always false
         */
        public function error($file, $message) {
            $file->setUploaded(false);
            $this->uploader->setState('error', new observr\Event($this, ['message' => $message]));
            
            return false;
        }

        /**
         * Main interface to store uploader state
         * @param callable $uploadTargetCallback
         * @return boolean
         */
        public function save($uploadTargetCallback = null) {
            $uploadDir = null;
            $success = true;

            $files = $this->uploader->getFiles();

            foreach($files as $file) {
                $name = $file->getName();

                if(is_callable($uploadTargetCallback)) {
                    $uploadDir = call_user_func_array($uploadTargetCallback, [$file]);
                }

                if(is_null($uploadTargetCallback) || empty($name)) {
                    $file->setName($file->getTemporaryName());
                }

                $success = ($this->saveFile($file, $uploadDir)) && $success;
            }

            return $success;
        }
        
        /**
         * Alias for move_uploaded_file
         * @param string $name
         * @param string $directory
         * @return boolean
         */
        protected function move($name, $directory) {
            return move_uploaded_file($name, $directory);
        }
    }
}