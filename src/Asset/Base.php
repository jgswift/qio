<?php
namespace qio\Asset {
    abstract class Base implements \qio\Asset {
        protected $cache;
        protected $content;
        protected $sourcePath;
        protected $sourceRoot;
        protected $lastModified;

        public function __construct(\qio\Cache $cache, $sourcePath = null) {
            $this->cache = $cache;
            $this->sourceRoot = new \qio\Directory(dirname($sourcePath));
            
            $this->setPath($sourcePath);
        }
        
        public function getCache() {
            return $this->cache;
        }
        
        public function getDefaultMode() {
            return \qio\Stream\Mode::Read;
        }

        public function getFilters() {
            return $this->cache->getFilters();
        }

        public function addFilter($filter) {
            $this->cache->addFilter($filter);

            return $this;
        }

        public function removeFilter($filter) {
            $this->cache->removeFilter($filter);

            return $this;
        }

        public function setFilters(array $filters = []) {
            $this->cache->setFilters($filters);

            return $this;
        }
        
        public function clearFilters() {
            $this->cache->clearFilters();
        }

        public function getContent() {
            return $this->content;
        }

        public function setContent($content) {
            $this->content = $content;
        }

        public function getDirectory() {
            return $this->sourceRoot;
        }

        public function getPath() {
            return $this->sourcePath;
        }

        public function setPath($path) {
            $this->sourcePath = $path;
        }

        public function getLastModified() {
            return $this->lastModified;
        }

        public function setLastModified($lastModified) {
            $this->lastModified = $lastModified;
        }
    }
}