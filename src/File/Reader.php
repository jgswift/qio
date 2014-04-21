<?php
namespace qio\File {
    class Reader extends \qio\Stream\Reader {
        /**
         * Default file refer
         * @param integer $length
         * @return string
         */
        public function read($length=null) {
            if(is_null($length) && $this->stream instanceof \qio\File\Stream) {
                $length = (int)$this->stream->getLength();
            } elseif(is_numeric($length)) {
                $length = (int)$length;
            }

            $this->buffer = fread($this->stream->getPointer(),
                                  $length);

            return $this->buffer;
        }

        /**
         * Alias for fgets
         * @param integer $length
         * @return string
         */
        public function gets($length=null) {
            if(!empty($length)) {
                return fgets($this->stream->getPointer(),$length);
            }

            return fgets($this->stream->getPointer());
        }

        /**
         * Uses stream_get_contents to retrieve whole stream
         * @return string
         */
        public function readAll() {
            return $this->readContents();
        }
        
        /**
         * Alias for stream_get_contents
         * @param integer $maxlength
         * @param integer $offset
         * @return string
         */
        public function readContents($maxlength=-1, $offset=-1) {
            return stream_get_contents($this->stream->getPointer(), $maxlength, $offset);
        }
        
        /**
         * Alias for stream_get_line
         * @param integer $length
         * @param string $ending
         * @return string
         */
        public function readLine($length, $ending=null) {
            return stream_get_line($this->stream->getPointer(), $length, $ending);
        }
    }
}

