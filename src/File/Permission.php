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
         * Helper function that generates standard string representation of permission flags
         * @return string
         */
        function __toString() {
            $value = $this->value();
            
            if ($this->hasFlag(self::Socket)) {
                // Socket
                $info = 's';
            } elseif ($this->hasFlag(self::Symbol)) {
                // Symbolic Link
                $info = 'l';
            } elseif ($this->hasFlag(self::Regular)) {
                // Regular
                $info = '-';
            } elseif ($this->hasFlag(self::Block)) {
                // Block special
                $info = 'b';
            } elseif ($this->hasFlag(self::Directory)) {
                // Directory
                $info = 'd';
            } elseif ($this->hasFlag(self::Character)) {
                // Character special
                $info = 'c';
            } elseif ($this->hasFlag(self::Pipe)) {
                // FIFO pipe
                $info = 'p';
            } else {
                // Unknown
                $info = 'u';
            }
            
            $info .= ($this->hasFlag(self::OwnerExecute) ? 'r' : '-');
            $info .= ($this->hasFlag(0x0080) ? 'w' : '-');
            $info .= ($this->hasFlag(0x0040) ?
                        ($this->hasFlag(0x0800) ? 's' : 'x' ) :
                        ($this->hasFlag(0x0800) ? 'S' : '-'));

            // Group
            $info .= ($this->hasFlag(self::GroupWrite) ? 'r' : '-');
            $info .= ($this->hasFlag(self::GroupExecute) ? 'w' : '-');
            $info .= ($this->hasFlag(0x0008) ?
                        ($this->hasFlag(0x0400) ? 's' : 'x' ) :
                        ($this->hasFlag(0x0400) ? 'S' : '-'));

            // World
            $info .= ($this->hasFlag(self::WorldRead) ? 'r' : '-');
            $info .= ($this->hasFlag(self::WorldWrite) ? 'w' : '-');
            $info .= ($this->hasFlag(self::WorldExecute) ?
                        ($this->hasFlag(0x0200) ? 't' : 'x' ) :
                        ($this->hasFlag(0x0200) ? 'T' : '-'));
            
            return $info;
        }
    }
}