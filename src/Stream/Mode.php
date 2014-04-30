<?php
namespace qio\Stream {
    use kenum;
    
    class Mode extends kenum\Enum\Base {
        const Read = 'r';
        const ReadWrite = 'r+';
        
        const WriteOnly = 'w';
        const ReadWriteTruncate = 'w+';
        
        const WriteOnlyFromEnd = 'a';
        const ReadWriteFromEnd = 'a+';
        
        const WriteOnlyEnsureNew = 'x';
        const ReadWriteEnsureNew = 'x+';
        
        const WriteNoLock = 'c';
        const ReadWriteLock = 'c+';
        
        /**
         * Examines mode for read characteristics
         * @return boolean
         */
        public function isRead() {
            return  $this->equals('r')  || 
                    $this->equals('r+') || 
                    $this->equals('w+') ||
                    $this->equals('a+') || 
                    $this->equals('x+') ||
                    $this->equals('c+');
        }
        
        /**
         * Examines mode for write characteristics
         * @return boolean
         */
        public function isWrite() {
            return  $this->equals('w')  || 
                    $this->equals('r+') || 
                    $this->equals('w+') ||
                    $this->equals('a')  || 
                    $this->equals('a+') || 
                    $this->equals('x')  || 
                    $this->equals('x+') ||
                    $this->equals('c')  ||
                    $this->equals('c+');
        }
    }
}