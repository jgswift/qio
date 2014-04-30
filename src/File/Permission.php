<?php
namespace qio\File {
    use kenum;
    
    class Permission extends kenum\Enum\Bitwise {
        const OwnerRead = 0x0400;
        const OwnerWrite = 0x0200;
        const OwnerExecute = 0x0100;
        
        const GroupRead = 0x0040;
        const GroupWrite = 0x0020;
        const GroupExecute = 0x0010;
        
        const WorldRead = 0x0004;
        const WorldWrite = 0x0002;
        const WorldExecute = 0x0001;
        
        const Socket = 0xC000;
        const Symbol = 0xA000;
        const Regular = 0x8000;
        const Block = 0x6000;
        const Directory = 0x4000;
        const Character = 0x2000;
        const Pipe = 0x1000;
        
        /**
         * Helper method that generates standard string representation of permission flags
         * @return string
         */
        function __toString() {
            $info = $this->getBaseString();
            
            $info .= $this->getExtraString();
            
            return $info;
        }
        
        /**
         * Helper method to generate permission string
         * @return string
         */
        private function getExtraString() {
            $extra = $this->getUserExtraString();
            
            $extra.= $this->getGroupExtraString();
            
            $extra.= $this->getWorldExtraString();
            
            return $extra;
        }
        
        /**
         * Helper method to generate user permission string
         * @return string
         */
        private function getUserExtraString() {
            $extra = ($this->hasFlag(self::OwnerExecute) ? 'r' : '-');
            $extra.= ($this->hasFlag(0x0080) ? 'w' : '-');
            $extra.= ($this->hasFlag(0x0040) ?
                        ($this->hasFlag(0x0800) ? 's' : 'x' ) :
                        ($this->hasFlag(0x0800) ? 'S' : '-'));
            
            return $extra;
        }
        
        /**
         * Helper method to generate group permission string
         * @return string
         */
        private function getGroupExtraString() {
            // Group
            $extra = ($this->hasFlag(self::GroupWrite) ? 'r' : '-');
            $extra.= ($this->hasFlag(self::GroupExecute) ? 'w' : '-');
            $extra.= ($this->hasFlag(0x0008) ?
                        ($this->hasFlag(0x0400) ? 's' : 'x' ) :
                        ($this->hasFlag(0x0400) ? 'S' : '-'));
            return $extra;
        }
        
        /**
         * Helper method to generate world permission string
         * @return string
         */
        private function getWorldExtraString() {
            // World
            $extra = ($this->hasFlag(self::WorldRead) ? 'r' : '-');
            $extra .= ($this->hasFlag(self::WorldWrite) ? 'w' : '-');
            $extra .= ($this->hasFlag(self::WorldExecute) ?
                        ($this->hasFlag(0x0200) ? 't' : 'x' ) :
                        ($this->hasFlag(0x0200) ? 'T' : '-'));
            return $extra;
        }
        
        /**
         * Helper method to generate base permission type string
         * @return string
         */
        private function getBaseString() {
            if ($this->hasFlag(self::Socket)) {
                // Socket
                return 's';
            } elseif ($this->hasFlag(self::Symbol)) {
                // Symbolic Link
                return 'l';
            } elseif ($this->hasFlag(self::Regular)) {
                // Regular
                return '-';
            } elseif ($this->hasFlag(self::Block)) {
                // Block special
                return 'b';
            } elseif ($this->hasFlag(self::Directory)) {
                // Directory
                return 'd';
            } elseif ($this->hasFlag(self::Character)) {
                // Character special
                return 'c';
            } elseif ($this->hasFlag(self::Pipe)) {
                // FIFO pipe
                return 'p';
            } else {
                // Unknown
                return 'u';
            }
        }
    }
}