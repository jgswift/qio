<?php
namespace qio {
    class File implements Resource
    {
        /**
         * Stores file path
         * @var string
         */
        protected $path;
        
        /**
         * Stores file parent directory
         * @var type 
         */
        protected $parent;
        
        /**
         * Stores file size
         * @var integer
         */
        protected $size;
        
        /**
         * Stores file info
         * @var array 
         */
        protected $pathinfo;
        
        /**
         * Default file constructor
         * @param string $path
         * @param \qio\IO\Directory $parent
         */
        public function __construct($path = null, IO\Directory $parent = null) {
            $this->path = $path;
            $this->parent = $parent;
        }

        /**
         * Default toString method
         * @return string
         */
        public function __toString() {
            if(is_string($this->path)){
                return $this->path;
            }
            return '';
        }

        /**
         * Retrieve file pathinfo
         * @return array
         */
        public function getPathInfo() {
            if(!is_null( $this->pathinfo)) {
                return $this->pathinfo;
            }

            return $this->pathinfo = pathinfo($this->path);
        }
        
        /**
         * Retrieves absolute path
         * @return string
         */
        public function getAbsolutePath() {
            return stream_resolve_include_path($this->path);
        }

        /**
         * Retrieve file path
         * @return string
         */
        public function getPath() {
            return $this->path;
        }
        
        /**
         * Retrieve file default mode
         * Always READ
         * @return integer
         */
        public function getDefaultMode() {
            return Stream\Mode::Read;
        }

        /**
         * Update file path
         * @param string $path
         */
        public function setPath($path) {
            $this->path = $path;
            unset($this->pathinfo);
        }

        /**
         * Helper method to generate string from size
         * @param integer $system 0 or 1 SizeView::IEC or SizeView::Binary
         * @param string $string SprintF format string
         * @return string
         */
        public function getSizeString($system = File\SizeView::IEC, $string = '%01.2f %s') {
            $sizeView = new File\SizeView($this->getSize(),$system,$string);
            return (string)$sizeView;
        }

        /**
         * Retrieve file size
         * @return boolean|integer
         */
        public function getSize() {
            if(isset($this->size)) {
                return $this->size;
            }

            if($this->exists()) {
                return $this->size = filesize($this->path);
            }

            return false;
        }

        /**
         * Update file size
         * @param integer $size
         * @throws \InvalidArgumentException
         */
        public function setSize($size) {
            if(is_numeric($size)) {
                $this->size = $size;
            } else  {
                throw new \InvalidArgumentException('The given size is not a number');
            }
        }

        /**
         * Check if file exists
         * @return boolean
         */
        public function exists() {
            return file_exists( $this->path );
        }

        /**
         * Check if file is resable
         * @return boolean
         */
        public function isReadable() {
            if($this->exists()) {
                return is_readable($this->path);
            }

            return false;
        }

        /**
         * Check if file is writable
         * @return boolean
         */
        public function isWritable() {
            if($this->exists()) {
                return is_writable($this->path);
            }
            
            return false;
        }

        /**
         * Deletes file from filesystem
         */
        public function delete() {
            if($this->exists()) {
                unlink($this->path);
            }
        }

        /**
         * Copy file to given path
         * @param string $path
         * @return \qio\File
         */
        public function copy($path) {
            if($this->exists()) {
                copy($this->path, $path);
                return new File($path, $this->getParent());
            }
        }

        /**
         * Copy file then delete current file (move)
         * @param string $path
         * @return \qio\File
         */
        public function move($path) {
            if($this->exists()) {
                $newfile = $this->copy($path);
                $this->delete();
                return $newfile;
            }
        }

        /**
         * Check if file is link
         * @return boolean
         */
        public function isLink() {
            return is_link($this->path);
        }

        /**
         * Retrieve extension from pathinfo
         * @return string
         */
        public function getExtension() {
                $info = $this->getPathInfo();
                if(isset($info['extension'])) {
                    return $info['extension'];
                }
                return '';
        }

        /**
         * Retrieve directory name from pathinfo
         * @return string
         */
        public function getDirectoryName() {
                $info = $this->getPathInfo();

                if(isset($info['dirname'])) {
                    return $info['dirname'];
                }

                return DS;
        }

        /**
         * Retrieve file directory
         * @return \qio\Directory
         */
        public function getParent() {
            if(!$this->hasParent()) {
                $this->setParent(new Directory($this->getDirectoryName()));
            }
            return $this->parent;
        }
        
        /**
         * Check if file has specified parent directory
         * @return boolean
         */
        public function hasParent() {
            return ($this->parent instanceof Directory) ? true : false;
        }

        /**
         * Retrieve basename from pathinfo
         * @return string
         */
        public function getBaseName() {
            $info = $this->getPathInfo();
            if(isset($info['basename'])) {
                return $info['basename'];
            }
            
            return '';
        }

        /**
         * Retrieve filename from pathinfo
         * @return string
         */
        public function getFileName() {
            $info = $this->getPathInfo();
            if(isset($info['filename'])) {
                return $info['filename'];
            }
            
            return '';
        }

        /**
         * Retrieve file realpath
         * @return string
         */
        public function getRealPath() {
            return realpath($this->path);
        }

        /**
         * Touch file to update last modified flag
         * @param integer $time
         * @param integer $atime
         * @return boolean
         */
        public function touch($time = null, $atime = null) {
            return touch($this->path , $time, $atime);
        }

        /**
         * Retrieve mode enum
         * @return \qio\File\Permission
         */
        public function getMode() {
            return new File\Permission(fileperms($this->path));
        }
        
        /**
         * Update files parent directory
         * @param \qio\Directory $directory
         */
        public function setParent(Directory $directory) {
            $this->parent = $directory;
        }

        /**
         * Update file mode
         * @param \qio\File\Permission|integer $mode
         * @return boolean
         */
        public function setMode($mode) {
            if($mode instanceof File\Permission) {
                $this->mode = $mode;
                $mode = $mode->value();
            } else {
                $this->mode = new File\Permission($mode);
            }
            return chmod($this->path , $mode);
        }

        /**
         * Retrieve file owner
         * @return integer
         */
        public function getOwner() {
            return fileowner($this->path);
        }

        /**
         * Update file owner
         * @param mixed $user
         * @return boolean
         */
        public function setOwner($user) {
            return chown($this->path, $user);
        }

        /**
         * Retrieve file last modified time
         * @return integer
         */
        public function getTime() {
            return filemtime($this->path);
        }

        /**
         * Retrieve file group
         * @return integer
         */
        public function getGroup() {
            return filegroup($this->path);
        }

        /**
         * Update file group
         * @param mixed $group
         * @return boolean
         */
        public function setGroup($group) {
            return chgrp($this->path ,$group);
        }

        /**
         * Retrieve last acces time of file
         * @return integer
         */
        public function getAccessTime() {
            return fileatime($this->path);
        }
    }
}