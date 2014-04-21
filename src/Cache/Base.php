<?php
namespace qio\Cache {
    use qtil, kfiltr, observr, qio;
    
    abstract class Base implements qio\Cache
    {
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
         * Stores local cache parameters
         * @var \qtil\Collection 
         */
        protected $parameters;
        
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
         * Construct base implementation of cache
         * @param array $params
         * @param array $rules
         * @param array $assets
         */
        public function __construct(array $params = [], array $rules = [], array $assets = []) {
            $this->parameters = new qtil\Collection();
            $this->rules = new qtil\Collection();
            $this->assets = new qtil\Collection();
            $this->parameters->merge($params);
            $this->assets->merge($assets);
            $this->rules->merge($rules);

            if($this->parameters->exists('rules') &&
               is_array($this->parameters['rules'])) {
                $this->rules->merge($this->parameters['rules']);
            }
            
            if($this->parameters->exists('assets')) {
                $this->assets->merge($this->parameters['assets']);
            }
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
            $this->assets->exchangeArray($assets);
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
            $this->rules->exchangeArray($rules);
        }

        /**
         * determines whether cache key is already prefixed
         * @param string $name
         * @return boolean
         */
        public function prefixed($name = null) {
            $prefixed = isset($this->parameters['prefix']);
            
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
        public function prefix( $name = '' ) {
            if(isset($this->parameters['prefix'])) {
                return $this->parameters['prefix'].$name;
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
         * @return \IO\Cache
         */
        public function enable() {
            $this->applyRules();
            $this->enabled = true;
            return $this;
        }

        /**
         * disable rules
         * @return \IO\Cache
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
         * @return \IO\Cache
         */
        public function applyRules(array $rules = []) {
            if(!empty($rules)) {
                $this->rules->fromArray($rules);
            }

            if(count($rules) > 0) {
                foreach($rules as $rule) {
                    if(is_callable($rule)) {
                        $rule->execute($this);
                    }
                }
            }

            return $this;
        }

        /**
         * 
         * @param string $offset
         * @return boolean
         */
        function offsetExists($offset)
        {
            if(!$this->prefixed($offset)) {
                $offset = $this->prefix($offset);
            }
            
            if(isset($this->data[$offset])) {
                return true;
            }

            if($this->parameters->exists('hash')) {
                $offset = $this->hash($offset);
            }

            $this->setState('has', $e = new observr\Event( $this,
                                    ['path' => $offset])
                                );

            if( !$e->canceled )
            {
                return $this->has($offset);
            }

            return false;
        }

        /**
         * 
         * @param string $offset
         * @param mixed $value
         */
        public function offsetSet($offset,$value) {
            $new = true;
            if( !$this->prefixed($offset) ) {
                $offset = $this->prefix($offset);
            }

            if($this->has($offset) || isset($this->data[$offset])) {
                $new = false;
            } else {
                $new = true;

                if(is_object($value) &&
                   in_array($value, (array)$this, true)) {
                    $new = false;
                }
            }

            $this->data[$offset] = $value;

            if($this->parameters->exists('hash')) {
                $offset = $this->hash($offset);
            }

            if($new === true) {
                $this->setState('save', $e = new observr\Event( $this,
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

            if(isset($this->data[$offset])) {
                return $this->data[$offset];
            }

            if($this->parameters->exists('hash')) {
                $offset = $this->hash( $offset );
            }

            $result = $this->load( $offset );
            $this->setState('load', $e = new observr\Event( $this,
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

            if(isset($this->data[$offset])) {
                unset($this->data[$offset]);
            }

            if($this->parameters->exists('hash')) {
                $offset = $this->hash($offset);
            }

            $this->setState('delete', $e = new observr\EventArgs($this,
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
        function hash($offset) {
            if(!$this->prefixed($offset)) {
                $offset = $this->prefix($offset);
            }

            if(!$this->parameters->exists('hash')) {
                return $offset;
            }

            $hash = $this->parameters['hash'];

            if(is_callable($hash)) {
                return $hash($offset);
            }

            return base64_encode($offset);
        }
    }
}