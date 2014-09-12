<?php
namespace qio\Directory {
    use qio;
    use qtil;
    
    /**
     * This class allows you to keep 2 directories synchronized according to customizable rules
     */
    class Cache extends qio\Asset\Cache {
        /**
         * Stores main source
         * @var \qio\Directory
         */
        protected $source;
        
        /**
         * Stores cache destination
         * @var \qio\Directory
         */
        protected $destination;
        
        /**
         * Constructor for directory cache,
         * @param \qio\Directory|string $directory
         * @param \qio\Directory|string $destination
         * @param array $rules
         * @param array $assets
         */
        public function __construct($directory, $destination, $hash = false, $prefix = '',array $rules = [], array $assets = []) {
            if(is_string($directory) && 
               is_dir($directory)) {
                $this->source = new qio\Directory($directory);
            } elseif($directory instanceof qio\Directory) {
                $this->source = $directory;
            }
            
            if(is_string($destination) &&
               is_dir($destination)) {
                $this->destination = new qio\Directory($destination);
            } elseif($directory instanceof qio\Directory) {
                $this->destination = $destination;
            }
            
            parent::__construct($hash, $prefix, $rules, $assets);
        }
        
        /**
         * Retrieve source directory
         * @return \qio\Directory
         */
        public function getDirectory() {
            return $this->source;
        }
        
        /**
         * Retrieve destination directory
         * @return \qio\Directory
         */
        public function getDestination() {
            return $this->destination;
        }

        /**
         * Checks if key exists in source
         * @param string $name
         * @return boolean
         */
        function has($name) {
            $path = $this->getPath($name);

            return is_file($path);
        }

        /**
         * load data from source
         * @param string $name
         * @return string
         */
        function load($name) {
            $stream = $this->getStream($name);
            $filereader = new qio\File\Reader($stream);
            $reader = new qio\Object\Serial\Reader($filereader);

            $stream->open();
            $buffer = $reader->read();
            $stream->close();

            return $buffer;
        }

        /**
         * save data to source
         * @param string $name
         * @param mixed $value
         */
        function save($name, $value) {
            $stream = $this->getStream($name, qio\Stream\Mode::ReadWriteTruncate);
            $filewriter = new qio\File\Writer($stream);
            $writer = new qio\Object\Serial\Writer($filewriter);
            
            $stream->open();

            $writer->write($value);

            $stream->close();
        }

        /**
         * delete data from source
         * @param string $name
         */
        function delete($name) {
            $path = $this->getPath($name);

            $file = new \qio\File($path);

            $file->delete();
        }
        
        /**
         * Retrieve path of cache item with additional filters applied
         * @param string $path
         * @return string
         */
        public function getPath($path) {
            $path = str_replace('\\',DIRECTORY_SEPARATOR,$path);
            $nfo = pathinfo($path);

            $basename = $nfo['basename'];
            
            $path = str_replace(DIRECTORY_SEPARATOR, '-', $basename);

            if(isset($this->data['hashing']) && 
               $this->data['hashing'] === true) {
                $path = md5($path);
            }

            if(isset($this->data['prepend']) && 
               is_string($this->data['prepend'])) {
                $path = $this->data['prepend'].'.'.$path;
            }
            
            $sourcePath = (string)$this->source;
            
            if(!qtil\StringUtil::endsWith($sourcePath,DIRECTORY_SEPARATOR)) {
                $sourcePath .= DIRECTORY_SEPARATOR;
            }

            return $sourcePath.$path;
        }

        /**
         * Helper function to create file stream from dynamic source path
         * @param string $name
         * @param string $mode
         * @return \qio\File\Stream
         */
        protected function getStream($name, $mode = qio\Stream\Mode::Read) {
            return new \qio\File\Stream($this->getPath($name), $mode);
        }

        /**
         * Retrieves cache destination of specific source asset
         * @param \qio\Asset $asset
         * @return \qio\File\Asset
         */
        public function getTarget(qio\Asset $asset) {
            $path = qtil\StringUtil::flipDS($asset->getPath());
            $nfo = pathinfo($path);

            $basename = $nfo['basename'];

            $targetPath = $this->getDestination()->getPath().DIRECTORY_SEPARATOR.$basename;
            
            return new \qio\File\Asset($this,$targetPath);
        }
    }
}