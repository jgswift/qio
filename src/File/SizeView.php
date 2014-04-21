<?php
namespace qio\File {
    class SizeView {
        const IEC = 1;
        const Binary = 2;
        
        /**
         * Stores sizeview unit, IEC | Binary
         * @var integer 
         */
        protected $unit;
        
        /**
         * Stores sizeview transformation string
         * @var string 
         */
        protected $string;
        
        /**
         * Stores file size
         * @var type 
         */
        protected $size;
        
        /**
         * Helper global to store common variables
         * @var array 
         */
        private static $systems;
        
        /**
         * Constructor for sizeview
         * @param integer $unit
         * @param string $string
         */
        function __construct($size, $unit=self::IEC, $string = '%01.2f %s') {
            $this->unit = $unit;
            $this->string = $string;
            $this->size = $size;
            
            if(empty(self::$systems)) {
                self::$systems = [];
                self::$systems[self::IEC]['prefix'] = ['B', 'Kb', 'Mb', 'Gb', 'Tb', 'Pb'];
                self::$systems[self::IEC]['size']   = 1000;
                self::$systems[self::Binary]['prefix'] = ['B', 'KB', 'MB', 'GB', 'TB', 'PB'];
                self::$systems[self::Binary]['size']   = 1024;
            }
        }
        
        /**
         * Retrieves size string
         * @return string
         */
        public function __toString() {
            $size = $this->size;
            $unit = $this->unit;
            
            $sys = isset(self::$systems[$unit]) 
                    ? self::$systems[$unit] 
                    : self::$systems[self::IEC];

            $depth = count($sys['prefix']) - 1;

            $i = 0;
            while ($size >= $sys['size'] && $i < $depth) {
                $size /= $sys['size'];
                $i++;
            }

            return sprintf($this->string, $size, $sys['prefix'][$i]);
        }
    }
}