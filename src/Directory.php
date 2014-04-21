<?php 
namespace qio {
    use qtil\StringUtil as Str;
    
    class Directory implements \qio\Resource, \IteratorAggregate {
        /**
         * Stores directory path, may be relative or absolute
         * @var string
         */
        protected $path;

        /**
         * Default constructor for directory base
         * @param string $path
         */
        function __construct($path) {
            if(!Str::endsWith($path, '/')) {
                $path .= '/';
            }

            if(!Str::startsWith($path, '/')) {
                $path = '/'.$path;
            }

            $cwd = getcwd();
            if(strpos($path, $cwd) === false) {
                $path = $cwd.$path;
            }

            $path = Str::flipDS($path);

            $this->path = $path;
        }
        
        /**
         * SPL provided iterator to provide default iteration
         * @return \DirectoryIterator
         */
        public function getIterator() {
            return new \DirectoryIterator($this->path);
        }
        
        /**
         * Checks if directory exists
         * Alias for is_dir
         * @return boolean
         */
        function exists() {
            return is_dir($this->path) ;
        }

        /**
         * Checks if directory is writable by current user
         * Alias for is_writable
         * @return boolean
         */
        function isWritable() {
            return is_writable($this->path);
        }

        /**
         * Checks if directory is readable by current user
         * Alias for is_readable
         * @return boolean
         */
        function isReadable() {
            return is_readable($this->path);
        }

        /**
         * Creates directory
         * Alias for mkdir
         * @return type
         */
        function create() {
            return mkdir($this->path);
        }
        
        /**
         * Deletes directory if it is empty
         * Returns whether or not delete operation was successful
         * @return boolean
         */
        protected function deleteEmpty() {
            if($this->exists()) {
                return rmdir($this->path);
            }

            return false;
        }

        /**
         * Recursively deletes folder contents
         * @return boolean
         */
        function delete() {
            if(!$this->exists()) {
                return;
            }
            
            $stream = new Directory\Stream($this);
            $reader = new Directory\Reader($stream);

            $stream->open();
            
            while($info = $reader->read()) {
                if($info instanceof File) {
                    unlink($info->path);
                } elseif($info instanceof Directory) {
                    $info->delete();
                }
            }

            $stream->close();
            return $this->deleteEmpty();
        }

        /**
         * Alias for basename
         * @return type
         */
        function getBaseName() {
            return basename($this->path);
        }

        /**
         * string transformation
         * @return string
         */
        function __toString() {
            return $this->getPath();
        }

        /**
         * Retrieves default stream mode
         * @return string
         */
        public function getDefaultMode() {
            return \qio\Stream\Mode::Read;
        }

        /**
         * Retrieve directory relative or absolute path
         * @return string
         */
        public function getPath() {
            return $this->path;
        }
    }
}

