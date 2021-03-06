<?php
namespace qio\Asset {
    use qio;
    
    abstract class Base implements \qio\Asset {
        
        /**
         * Stores asset cache
         * @var qio\Asset\Cache 
         */
        protected $cache;
        protected $content;
        protected $sourcePath;
        protected $sourceRoot;
        protected $lastModified;

        /**
         * Base asset constructor
         * @param \qio\Cache $cache
         * @param string $sourcePath
         */
        public function __construct($cache, $sourcePath = null) {
            $this->cache = $cache;
            
            $this->sourceRoot = new qio\Directory(dirname($sourcePath));
            
            if($sourcePath instanceof qio\Resource) {
                $sourcePath = $sourcePath->getPath();
            }
            
            $this->setPath($sourcePath);
        }
        
        /**
         * Retrieve cache
         * @return \qio\Asset\Cache
         */
        public function getCache() {
            return $this->cache;
        }
        
        /**
         * Default mode
         * @return string
         */
        public function getDefaultMode() {
            return \qio\Stream\Mode::Read;
        }

        /**
         * Alias for \qio\Cache::getFilters
         * @return type
         */
        public function getFilters() {
            return $this->cache->getFilters();
        }

        /**
         * Alias for qio\Asset\Cache::addFilter
         * @param mixed $filter
         * @return \qio\Asset\Base
         */
        public function addFilter($filter) {
            $this->cache->addFilter($filter);

            return $this;
        }

        /**
         * Alias for qio\Asset\Cache::removeFilter
         * @param mixed $filter
         * @return \qio\Asset\Base
         */
        public function removeFilter($filter) {
            $this->cache->removeFilter($filter);

            return $this;
        }

        /**
         * Alias for qio\Asset\Cache::setFilters
         * @param array $filters
         * @return \qio\Asset\Base
         */
        public function setFilters(array $filters = []) {
            $this->cache->setFilters($filters);

            return $this;
        }
        
        /**
         * Alias for qio\Asset\Cache::clearFilters
         */
        public function clearFilters() {
            $this->cache->clearFilters();
        }

        /**
         * Retrieve asset content
         * @return string
         */
        public function getContent() {
            return $this->content;
        }

        /**
         * Update asset content
         * @param string $content
         */
        public function setContent($content) {
            $this->content = $content;
        }

        /**
         * Retrieve source directory
         * @return \qio\Directory
         */
        public function getDirectory() {
            return $this->sourceRoot;
        }

        /**
         * Retrieve source path
         * @return string
         */
        public function getPath() {
            return $this->sourcePath;
        }

        /**
         * Update source path
         * @param string $path
         */
        public function setPath($path) {
            $this->sourcePath = $path;
        }

        /**
         * Retrieve time last modified
         * @return string
         */
        public function getLastModified() {
            return $this->lastModified;
        }

        /**
         * Update time last modified
         * @param string $lastModified
         */
        public function setLastModified($lastModified) {
            $this->lastModified = $lastModified;
        }
    }
}