<?php
namespace qio {
    interface Cache extends \ArrayAccess
    {
        const STATE_HAS = 'has';
        const STATE_LOAD = 'load';
        const STATE_SAVE = 'save';
        const STATE_DELETE = 'delete';
        
        /**
         * translates source asset and provides target destination path
         */
        function getTarget(Asset $asset);
        
        /**
         * Retrieve all cache assets
         */
        public function getAssets();
        
        /**
         * Updates all cache assets
         */
        public function setAssets(array $assets);
        
        /**
         * Retrieves current cache rules
         */
        public function getRules();
        
        /**
         * Updates current cache rules
         */
        public function setRules(array $rules);

        /**
         * determines whether cache key is already prefixed
         * @param string $name
         * @return boolean
         */
        public function prefixed($name = null);

        /**
         * prepends prefix to cache key
         * @param string $name
         * @return string
         */
        public function prefix($name = '');
        
        /**
         * Checks if cache rules are enabled
         */
        public function isEnabled();

        /**
         * enable rules
         */
        public function enable();

        /**
         * disable rules
         */
        public function disable();

        /**
         * executes all rules, enabling them
         * @param array $rules
         */
        public function applyRules(array $rules = []);

        /**
         * checks if item is available in cache
         * @param string $id
         * @return boolean
         */
        function has($id);

        /**
         * loads item from cache into memory
         * @param string $id
         * @return boolean
         */
        function load($id);

        /**
         * saves item from memory into cache
         * @param string $id
         * @param mixed $value
         * @return boolean
         */
        function save($id,$value);

        /**
         * deletes item from cache permanently
         * @param string $id
         * @return boolean
         */
        function delete($id);


        /**
         * uses cache key to generate hash
         * @param string $offset
         * @return string
         */
        function hash($offset);
    }
}