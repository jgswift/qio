<?php
namespace qio\Stream {
    use qio;
    
    class Context extends qio\Context {
        
        /**
         * Stores context resource
         * @var resource 
         */
        private $context;
        
        /**
         * Stores context wrapper protocol
         * @var type 
         */
        private $wrapper;
        
        /**
         * 
         * @param array $options
         * @param array $params
         * @param string $wrapper
         */
        public function __construct(
                array $options = array(), 
                array $params = array(), 
                $wrapper = 'wrap'
        ) {
            $this->wrapper = $wrapper;
            parent::__construct($options, $params);
        }
        
        /**
         * Creates context resource
         * @return resource
         */
        public function create() {
            $this->context = stream_context_create(
                    $this->options->toArray(),
                    $this->data->toArray()
                );
            
            $this->initialize();
            
            return $this->context;
        }
        
        /**
         * Checks if context was already created
         * @return type
         */
        public function isCreated() {
            return !empty($this->context) ? true : false;
        }
        
        /**
         * Initializes observr collections by aliasing
         * stream_context_set_option and stream_context_set_params
         */
        private function initialize() {
            $this->options->merge(stream_context_get_options($this->context));
            $this->options->attach('set',function($sender,$e) {
                stream_context_set_option(
                        $this->context,
                        $this->wrapper,
                        $e->offset,
                        $e->value
                    );
            });
            
            $this->data->merge(stream_context_get_params($this->context));
            $this->data->attach('set',function($sender,$e) {
                stream_context_set_params(
                        $this->context, 
                        $this->data->toArray()
                    );
            });
        }
    }
}