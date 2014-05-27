<?php
namespace qio\Memory {
    use qio;
    use qtil;
    
    class Cache implements qio\Cache\Base {
        use qtil\ArrayAccess;
        
        /**
         * Host address
         * @var string 
         */
        protected $host;
        
        /**
         * Host port
         * @var integer 
         */
        protected $port;
        
        /**
         * Connection timeout
         * @var integer|null
         */
        protected $timeout = null;
        
        /**
         * Local memcache store
         * @var \Memcache 
         */
        protected $memcache;
        
        /**
         * Cache enabled check
         * @var boolean 
         */
        private $enabled = false;
        
        /**
         * Default memcache constructor
         * @param string $host
         * @param integer $port
         * @param integer|null $timeout
         * @throws qio\Exception
         */
        public function __construct($host,$port=11211,$timeout=null) {
            $this->host = $host;
            $this->port = $port;
            $this->timeout = $timeout;
            if(!extension_loaded('memcache')) {
                throw qio\Exception('Cannot load Memory Cache, memcache extesion not found');
            }
            
            $this->memcache = new \Memcache;
        }

        /**
         * Delete item from cache
         * @param string $id
         */
        public function delete($id) {
            $this->memcache->delete($id);
            $this->memcache->delete('check_'.$id);
        }

        /**
         * Disable cache
         */
        public function disable() {
            $this->enabled = false;
            $this->memcache->close();
        }

        /**
         * Enable cache
         */
        public function enable() {
            $this->enabled = true;
            $this->memcache->connect($this->host, $this->port, $this->timeout);
        }

        /**
         * Checks if cache current contains item
         * @param string $id
         * @return boolean
         */
        public function has($id) {
            return !empty($this->memcache->get('check_'.$id));
        }

        /**
         * Check if cache is enabled
         * @return boolean
         */
        public function isEnabled() {
            return $this->enabled;
        }

        /**
         * Get item from cache
         * @param string $id
         * @return mixed
         */
        public function load($id) {
            return $this->memcache->get($id);
        }
        
        /**
         * Set item in cache
         * @param string $id
         * @param mixed $value
         */
        public function save($id,$value) {
            $this->memcache->set('check_'.$id, true);
            $this->memcache->set($id,$value);
        }
    }
}