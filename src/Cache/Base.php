<?php
namespace qio\Cache {
    use qio;
    use qtil;
    
    abstract class Base implements qio\Cache {
        use qtil\ArrayAccess {
            qtil\ArrayAccess::offsetExists as _offsetExists;
            qtil\ArrayAccess::offsetSet as _offsetSet;
            qtil\ArrayAccess::offsetUnset as _offsetUnset;
            qtil\ArrayAccess::offsetGet as _offsetGet;
        }
        
        /**
         * 
         * @param string $offset
         * @return boolean
         */
        function offsetExists($offset) {
            if($this->_offsetExists($offset)) {
                return true;
            }
            
            return $this->has($offset);
        }

        /**
         * 
         * @param string $offset
         * @param mixed $value
         */
        public function offsetSet($offset,$value) {
            $this->_offsetSet($offset,$value);
            $this->save($offset,$value);
        }

        /**
         * 
         * @param string $offset
         * @return mixed
         */
        public function offsetGet($offset) {
            if($this->_offsetExists($offset)) {
                return $this->_offsetGet($offset);
            }
            
            return $this->load($offset);
        }

        /**
         * 
         * @param string $offset
         */
        public function offsetUnset($offset) {
            $this->_offsetUnset($offset);
            $this->delete($offset);
        }
    }
}