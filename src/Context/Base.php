<?php
namespace qio\Context {
    use qio;
    use qtil;
    use observr;
    use kenum;
    
    abstract class Base extends kenum\Enum\Base implements qio\Context {
        use qtil\ArrayAccess, qtil\Countable;
        
        const CURL = 'curl';
        const FTP = 'ftp';
        const File = 'file';
        const HTTP = 'http';
        const Phar = 'phar';
        const SSL = 'ssl';
        const Socket = 'socket';
        
        /**
         * Stores context options
         * @var \observr\Collection
         */
        public $options;
        
        /**
         * Stores context data
         * @var \observr\Collection 
         */
        public $data;
        
        /**
         * Default constructor for IO context
         * @param array $options
         * @param array $params
         */
        function __construct(array $options = [], array $params = []) {
            parent::__construct();
            $this->options = new observr\Collection($options);
            $this->data = new observr\Collection($params);
        }
        
        /**
         * retrieve context parameters
         * @return \observr\Collection
         */
        public function getParameters() {
            return $this->data;
        }
        
        /**
         * retrieve context options
         * @return \observr\Collection
         */
        public function getOptions() {
            return $this->options;
        }
    }
}


