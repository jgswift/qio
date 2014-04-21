<?php
namespace qio\Memory {
    class Reader extends \qio\File\Reader {
        
        /**
         * reads single byte from stream
         * @return integer
         */
        public function readByte() {
            $value = ord(parent::read(1));
            return $value;
        }
        
        /**
         * read multiple bytes from stream
         * @param integer $length
         * @return array
         */
        public function readBytes($length) {
            $bytes = [];
            for($i=0;$i<$length;$i++) {
                $bytes[] = $this->readByte();
            }
            
            return $bytes;
        }
        
        /**
         * reads short from stream
         * @return integer
         */
        public function readShort() {
            return $this->readByte() | $this->readByte()<<8;
        }
        
        /**
         * reads integer from stream
         * @return integer
         */
        public function readInteger() {
            return $this->readByte() | $this->readByte()<<8 | $this->readByte() << 16 | $this->readByte() << 24;
        }
        
        /**
         * Alias for readFloat
         * @return float
         */
        public function readLong() {
            return $this->readInteger();
        }
        
        /**
         * reads float from stream
         * @return float
         */
        public function readFloat() {
            $int = $this->readInteger();
            $dec = $this->readInteger();
            
            return floatval($int.'.'.$dec);
        }
        
        /**
         * reads object from stream
         * @return object
         */
        public function readObject() {
            return unserialize($this->readString());
        }
        
        /**
         * Alias for readFloat
         * @return float
         */
        public function readDouble() {
            return $this->readFloat();
        }
        
        /**
         * readByte translated to boolean
         * @return boolean
         */
        public function readBoolean() {
            return (bool)$this->readByte();
        }
        
        /**
         * read string from stream
         * @return string
         */
        public function readString() {
            $length = $this->readInteger();
            $string = '';
            if($length > 0) {
                for($i=0;$i<$length;$i++) {
                    $string .= chr($this->readInteger());
                }
            }
            
            return $string;
        }
    }
}
