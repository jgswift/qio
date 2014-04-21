<?php
//TODO: use kenum bitwise enum to represent file permissions
namespace qio {
    class File implements Resource
    {
        protected $path;
        protected $parent;
        protected $size;
        protected $pathinfo;
        
        function __construct($path = null, IO\Directory $parent = null) {
            $this->path = $path;
            $this->parent = $parent;
        }

        function __toString() {
            return $this->path;
        }

        function getPathInfo() {
            if(!is_null( $this->pathinfo)) {
                return $this->pathinfo;
            }

            return $this->pathinfo = pathinfo($this->path);
        }
        
        public function getAbsolutePath() {
            return stream_resolve_include_path($this->path);
        }

        public function getPath() {
            return $this->path;
        }
        
        public function getDefaultMode() {
            return Stream\Mode::Read;
        }

        public function setPath($path) {
            $this->path = $path;
            unset($this->pathinfo);
        }

        function getSizeString($system = 'si', $string = '%01.2f %s') {
            $sizeView = new File\SizeView($this->getSize(),$system,$string);
            return (string)$sizeView;
        }

        function getSize() {
            if(isset($this->size)) {
                return $this->size;
            }

            if($this->exists()) {
                return $this->size = filesize($this->path);
            }

            return false;
        }

        function setSize($size) {
            if(is_numeric($size)) {
                $this->size = $size;
            } else  {
                throw new \InvalidArgumentException('The given size is not a number');
            }
        }

        function exists() {
            return file_exists( $this->path );
        }

        function isReadable() {
            if($this->exists()) {
                return is_readable($this->path);
            }

            return false;
        }

        function isWritable() {
            if($this->exists()) {
                return is_writable($this->path);
            }
            
            return false;
        }

        function delete() {
            if($this->exists()) {
                unlink($this->path);
            }
        }

        function copy($path) {
            if($this->exists()) {
                copy($this->path, $path);
                return new File($path, $this->getParent());
            }
        }

        function move($path) {
            if($this->exists()) {
                $newfile = $this->copy($path);
                $this->delete();
                return $newfile;
            }
        }

        function isLink() {
            return is_link($this->path);
        }

        function getExtension() {
                $info = $this->getPathInfo();
                if(isset($info['extension'])) {
                    return $info['extension'];
                }
                return '';
        }

        function getDirectoryName() {
                $info = $this->getPathInfo();

                if(isset($info['dirname'])) {
                    return $info['dirname'];
                }

                return DS;
        }

        function getParent() {
            if(!$this->hasParent()) {
                $this->setParent(new Directory($this->getDirectoryName()));
            }

            return parent::getParent();
        }

        function getBaseName() {
            $info = $this->getPathInfo();
            if(isset($info['basename'])) {
                return $info['basename'];
            }
            
            return '';
        }

        function getFileName() {
            $info = $this->getPathInfo();
            if(isset($info['filename'])) {
                return $info['filename'];
            }
            
            return '';
        }

        function getRealPath() {
            return realpath( $this->path  );
        }

        function touch($time = null, $atime = null) {
            return touch($this->path , $time, $atime);
        }

        function getMode() {
            return new File\Permission(fileperms($this->path));
        }

        function setMode(File\Permission $mode) {
            return chmod($this->path , $mode->value());
        }

        function getOwner() {
            return fileowner($this->path);
        }

        function setOwner($user) {
            return chown($this->path, $user);
        }

        function getTime() {
            return filemtime($this->path);
        }

        function getGroup() {
            return filegroup($this->path);
        }

        function setGroup($group) {
            return chgrp($this->path ,$group);
        }

        function getAccessTime() {
            return fileatime($this->path);
        }
    }
}