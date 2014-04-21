<?php
namespace qio\Memory {
    class Writer extends \qio\File\Writer {
        /**
         * write single byte to stream
         * @param mixed $byte
         */
        public function writeByte($byte) {
            parent::write(chr($byte));
        }
        
        /**
         * write multiple bytes from stream
         */
        public function writeBytes() {
            if(func_num_args() > 0) {
                $args = func_get_args();
                foreach($args as $arg) {
                    $this->writeByte($arg);
                }
            }
        }
        
        /**
         * write boolean to stream
         * @param boolean $bool
         */
        public function writeBoolean($bool) {
            $this->writeByte((int)$bool);
        }
        
        /**
         * write string to stream
         * @param string $string
         */
        public function writeString($string) {
            $length = strlen($string);
            $this->writeInteger($length);
            for ($i = 0; $i < $length; $i++) {
                $this->writeInteger(ord($string[$i]));
            }
        }
        
        /**
         * write short to stream
         * @param integer $short
         */
        public function writeShort($short) {
            if(!is_int($short)) {
                $short = (int)$short;
            }
            
            $this->writeBytes($short, ($short>>8));
        }
        
        /**
         * write integer to stream
         * @param integer $int
         */
        public function writeInteger($int) {
            if(!is_int($int)) {
                $int = (int)$int;
            }
            
            $this->writeBytes($int, ($int>>8), ($int>>16),($int>>24));
        }
        
        /**
         * Alias for writeLong
         * @param type $long
         */
        public function writeLong($long) {
            $this->writeInteger($long);
        }
        
        /**
         * writes object to stream
         * @param object $object
         */
        public function writeObject($object) {
            $this->writeString(serialize($object));
        }
        
        /**
         * write float to stream
         * @param float $float
         */
        public function writeFloat($float) {
            if(is_float($float)) {
                $parts = explode(".",(string)$float);
                $this->writeInteger((integer)$parts[0]);
                $this->writeInteger((integer)$parts[1]);
            } elseif(is_numeric($float)) {
                $this->writeInteger((integer)$float);
                $this->writeInteger(0);
            }
        }
        
        /**
         * Alias for writeFloat
         * @param float $double
         */
        public function writeDouble($double) {
            $this->writeFloat($double); 
        }
    }
}
