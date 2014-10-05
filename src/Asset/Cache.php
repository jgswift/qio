<?php
namespace qio\Asset {
    use qio;
    use qtil;
    use kfiltr;
    use observr;
    use observr\Subject\SubjectInterface;
    use qio\Cache\Event;
    
    abstract class Cache extends qio\Cache\Base implements SubjectInterface {
        use observr\Subject, kfiltr\Hook;

        /**
         * Whether or not cache is enabled
         * @var boolean 
         */
        protected $enabled = false;

        /**
         * Stores in-memory cached items to avoid multiple retrieval
         * @var array 
         */
        protected $data = [];
        
        /**
         * Transient list of cache rules
         * @var \qtil\Collection 
         */
        protected $rules;
        
        /**
         * Stores list of assets used by cache
         * @var \qtil\Collection  
         */
        protected $assets;
        
        /**
         * Prefix to use for cache keys
         * @var string
         */
        protected $prefix;
        
        /**
         * Whether cache hashes keys
         * @var callable|boolean
         */
        protected $hash = false;

        /**
         * Construct base implementation of cache
         * @param callable|boolean $hash
         * @param string $prefix
         * @param array $rules
         * @param array $assets
         */
        public function __construct($hash = false, $prefix = '', array $rules = [], array $assets = []) {
            $this->hash = $hash;
            $this->prefix = $prefix;
            $this->rules = new qtil\Collection();
            $this->assets = new qtil\Collection();
            $this->assets->merge($assets);
            $this->rules->merge($rules);
        }
        
        /**
         * Overriden, must supply new asset located at cached destination
         */
        abstract function getTarget(qio\Asset $asset);
        
        /**
         * Retrieves cache assets
         * @return \qtil\Collection
         */
        public function getAssets() {
            return $this->assets;
        }
        
        /**
         * Updates cache assets
         * @param array $assets
         */
        public function setAssets(array $assets) {
            $this->assets->fromArray($assets);
        }
        
        /**
         * Retrieves cache rules
         * @return \qtil\Collection 
         */
        public function getRules() {
            return $this->rules;
        }
        
        /**
         * Updates cache rules
         * @param array $rules
         */
        public function setRules(array $rules) {
            $this->rules->fromArray($rules);
        }

        /**
         * determines whether cache key is already prefixed
         * @param string $name
         * @return boolean
         */
        public function prefixed($name = null) {
            $prefixed = !empty($this->prefix);
            
            if( $prefixed && !is_null($name))  {
                return strpos($name,$prefixed) === 0 ? true : false;
            } elseif( !is_null($name)) {
                return $name;
            }
            
            return $prefixed;
        }

        /**
         * prepends prefix to cache key
         * @param string $name
         * @return string
         */
        public function prefix($name = '') {
            if(!empty($this->prefix)) {
                return $this->prefix.$name;
            }

            return '';
        }
        
        /**
         * Checks if cache is enabled
         * @return boolean
         */
        public function isEnabled() {
            return $this->enabled;
        }

        /**
         * enable rules
         * @return Base
         */
        public function enable() {
            $this->applyRules();
            $this->enabled = true;
            return $this;
        }

        /**
         * disable rules
         * @return Base
         */
        public function disable() {
            $this->enabled = false;
            $this->clearState('has');
            $this->clearState('load');
            $this->clearState('save');
            $this->clearState('delete');

            return $this;
        }

        /**
         * executes all rules, enabling them
         * @param array $rules
         * @return Base
         */
        public function applyRules(array $rules = []) {
            if(!empty($rules)) {
                $this->rules->fromArray($rules);
            }

            if(count($rules) > 0) {
                foreach($rules as $rule) {
                    if(is_callable($rule)) {
                        $rule($this);
                    }
                }
            }

            return $this;
        }
        
        protected function checkNew($offset) {
            $new = true;
            
            if($this->has($offset) || 
               parent::offsetExists($offset)) {
                $new = false;
            }
            
            return $new;
        }

        /**
         * 
         * @param string $offset
         * @return boolean
         */
        public function offsetExists($offset) {
            if(!$this->prefixed($offset)) {
                $offset = $this->prefix($offset);
            }
            
            if(parent::offsetExists($offset)) {
                return true;
            }

            if($this->hash !== false) {
                $offset = $this->hash($offset);
            }

            $this->setState('has', $e = new Event( $this,
                                    ['path' => $offset])
                                );

            if(!$e->canceled) {
                return (boolean)$this->has($offset);
            }

            return false;
        }

        /**
         * 
         * @param string $offset
         * @param mixed $value
         */
        public function offsetSet($offset,$value) {
            if(!$this->prefixed($offset)) {
                $offset = $this->prefix($offset);
            }

            $new = $this->checkNew($offset);

            parent::offsetSet($offset, $value);

            if($this->hash !== false) {
                $offset = $this->hash($offset);
            }

            if($new === true) {
                $this->setState('save', $e = new Event( $this,
                                    ['path' => $offset,
                                     'value'=>$value])
                                );
                if(!$e->canceled) {
                    $this->save($e->path, $e->value);
                }
            }
        }

        /**
         * 
         * @param string $offset
         * @return mixed
         */
        public function offsetGet($offset) {
            if(!$this->prefixed($offset)) {
                $offset = $this->prefix($offset);
            }

            if(parent::offsetExists($offset)) {
                return parent::offsetGet($offset);
            }

            if($this->hash !== false) {
                $offset = $this->hash($offset);
            }

            $result = $this->load( $offset );
            $this->setState('load', $e = new Event( $this,
                                    ['path' => $offset,
                                     'value'=>$result])
                                );
            if(!$e->canceled) {
                return $e->value;
            }
        }

        /**
         * 
         * @param string $offset
         */
        public function offsetUnset( $offset )
        {
            if(!$this->prefixed($offset)) {
                $offset = $this->prefix($offset);
            }

            if(parent::offsetExists($offset)) {
                parent::offsetUnset($offset);
            }

            if($this->hash !== false) {
                $offset = $this->hash($offset);
            }

            $this->setState('delete', $e = new Event($this,
                                        ['path' => $offset])
                                    );
            
            if(!$e->canceled) {
                $this->delete($e->path);
            }
        }

        /**
         * uses cache key to generate hash
         * @param string $offset
         * @return string
         */
        public function hash($offset) {
            if(!$this->prefixed($offset)) {
                $offset = $this->prefix($offset);
            }

            if($this->hash === false) {
                return $offset;
            }

            $hash = $this->hash;

            if(is_callable($hash)) {
                return $hash($offset);
            }

            return base64_encode($offset);
        }
    }
}
