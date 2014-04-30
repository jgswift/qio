<?php
namespace qio\Directory {
    use qio;
    
    /**
     * This class allows you to keep 2 directories synchronized according to customizable rules
     */
    class Cache extends qio\Cache\Base {
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
         * @param array $params
         * @param array $rules
         * @param array $assets
         */
        public function __construct($directory, $destination, array $params = [], array $rules = [], array $assets = []) {
            if(is_string($directory) && 
               is_dir($directory)) {
                $this->source = new \qio\Directory($directory);
            } elseif($directory instanceof \qio\Directory) {
                $this->source = $directory;
            }
            
            if(is_string($destination) &&
               is_dir($destination)) {
                $this->destination = new \qio\Directory($destination);
            } elseif($directory instanceof \qio\Directory) {
                $this->destination = $destination;
            }
            
            parent::__construct($params, $rules, $assets);
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
            $reader = new \qio\File\Reader($stream);

            $stream->open();
            $contents = $reader->readAll();
            $stream->close();

            return $contents;
        }

        /**
         * save data to source
         * @param string $name
         * @param mixed $value
         */
        function save($name, $value) {
            if(!is_string($value)) {
                $value = (string)$value;
            }

            $stream = $this->getStream($name , \IO\Stream\Mode::Write );
            $writer = new \qio\File\Writer( $stream );

            $stream->open();

            $writer->write( $value );

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
        public function getPath($path)
        {
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

            return (string)$this->source.DIRECTORY_SEPARATOR.$path;
        }

        /**
         * Helper function to create file stream from dynamic source path
         * @param string $name
         * @param string $mode
         * @return \qio\File\Stream
         */
        protected function getStream($name, $mode = \IO\Stream\Mode::Read)
        {
            return new \qio\File\Stream($this->getPath($name), $mode);
        }

        /**
         * Retrieves cache destination of specific source asset
         * @param \qio\Asset $asset
         * @return \qio\File\Asset
         */
        public function getTarget(\qio\Asset $asset) {
            $path = str_replace('\\',DIRECTORY_SEPARATOR,$asset->getPath());
            $nfo = pathinfo($path);

            $basename = $nfo['basename'];

            $targetPath = $this->getDestination()->getPath().DIRECTORY_SEPARATOR.$basename;
            
            return new \qio\File\Asset($this,$targetPath);
        }
    }
}