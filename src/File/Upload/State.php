<?php
namespace qio\File\Upload {
    use observr;
    
    class State {
        private $uploader;
        
        /**
         * Constructor for uploader utilty state
         * @param \qio\Upload $uploader
         */
        function __construct(\qio\Upload $uploader) {
            $this->uploader = $uploader;
        }
        
        /**
         * Helper function to complete uploads
         * @param \qio\File\Upload\Upload\File $file
         * @param string $uploadDirectory
         * @return boolean
         */
        protected function saveFile(Upload\File $file, $uploadDirectory = null) {
            if($file->getErrorCode() !== ExceptionMode::OK) {
                $file->setUploaded(false);
                $this->uploader->setState('error', new observr\Event($this->uploader, ['message' => $file->getErrorMessage()]));
                return false;
            }

            $oldDirectory = $this->uploader->getUploadDirectory();

            if(!is_null($uploadDirectory)) {
                try {
                    $this->uploader->setUploadDirectory($uploadDirectory);
                } catch(\InvalidArgumentException $e) {
                    
                    $file->setUploaded(false);
                    $this->uploader->setState('error', new observr\Event($this->uploader, ['message' => $e->getMessage()]));
                    
                } finally {
                    $this->uploader->forceUploadDirectory($oldDirectory);
                    
                    return false;
                }
            }

            $this->setState('constraint', $e = new observr\Event($this->uploader, ['file' => $file]));

            if($e->canceled) {
                $this->uploader->forceUploadDirectory($oldDirectory);
                $file->setUploaded(false);
                $this->uploader->setState('error', new observr\Event($this, ['message' => 'File upload constraint not valid']));
                return false;
            }

            $path = $this->uploader->getUploadDirectory() . $file->getFileName().'.'.$file->getExtension();

            $uploaded = $this->move($file->getTemporaryName(), $path);
            $this->uploader->forceUploadDirectory($oldDirectory);

            if($uploaded) {
                $file->setPath($path);
                $file->setUploaded(true);
                return true;
            } else {
                $this->uploader->setState('error', new observr\Event($this, ['message' => 'Unable to move file']));
                $file->setUploaded(false);
                return false;
            }
        }

        /**
         * Main interface to store uploader state
         * @param callable $uploadTargetCallback
         * @return boolean
         */
        function save($uploadTargetCallback = null) {
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