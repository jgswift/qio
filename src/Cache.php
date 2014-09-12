<?php
namespace qio {
    interface Cache extends \ArrayAccess {
        const STATE_HAS = 'has';
        const STATE_LOAD = 'load';
        const STATE_SAVE = 'save';
        const STATE_DELETE = 'delete';

        /**
         * Checks if cache rules are enabled
         * @return boolean
         */
        public function isEnabled();

        /**
         * enable rules
         * @return Cache\Base
         */
        public function enable();

        /**
         * disable rules
         * @return Cache\Base
         */
        public function disable();

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
    }
}