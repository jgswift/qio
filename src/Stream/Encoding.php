<?php
namespace qio\Stream {
    use kenum;
    
    class Encoding extends kenum\Enum\Base
    {
        const PASS              = 'pass';
        const AUTO              = 'auto';
        const WCHAR             = 'wchar';
        const BYTE2BE           = 'byte2be';
        const BYTE2LE           = 'byte2le';
        const BYTE4BE           = 'byte4be';
        const BYTE4LE           = 'byte4le';
        const BASE64            = 'BASE64';
        const UUENCODE          = 'UUENCODE';
        const HTML_ENTITIES     = 'HTML-ENTITIES';
        const QUOTED_PRINTABLE  = 'Quoted-Printable';
        const BIT7              = '7bit';
        const BIT8              = '8bit';
        const UCS_4             = 'UCS-4';
        const UCS_4BE           = 'UCS-4BE';
        const UCS_4LE           = 'UCS-4LE';
        const UCS_2             = 'UCS-2';
        const UCS_2BE           = 'UCS-2BE';
        const UCS_2LE           = 'UCS-2LE';
        const UTF_32            = 'UTF-32';
        const UTF_32BE          = 'UTF-32BE';
        const UTF_32LE          = 'UTF-32LE';
        const UTF_16            = 'UTF-16';
        const UTF_16BE          = 'UTF-16BE';
        const UTF_16LE          = 'UTF-16LE';
        const UTF_8             = 'UTF-8';
        const UTF_7             = 'UTF-7';
        const UTF7_IMAP         = 'UTF7-IMAP';
        const ASCII             = 'ASCII';
        const EUC_JP            = 'EUC-JP';
        const SJIS              = 'SJIS';
        const EUCJP_WIN         = 'eucJP-win';
        const SJIS_WIN          = 'SJIS-win';
        const CP51932           = 'CP51932';
        const JIS               = 'JIS';
        const ISO_2022_JP       = 'ISO-2022-JP';
        const ISO_2022_JP_MS    = 'ISO-2022-JP-MS';
        const WINDOWS_1252      = 'Windows-1252';
        const WINDOWS_1254      = 'Windows-1254';
        const ISO_8859_1        = 'ISO-8859-1';
        const ISO_8859_2        = 'ISO-8859-2';
        const ISO_8859_3        = 'ISO-8859-3';
        const ISO_8859_4        = 'ISO-8859-4';
        const ISO_8859_5        = 'ISO-8859-5';
        const ISO_8859_6        = 'ISO-8859-6';
        const ISO_8859_7        = 'ISO-8859-7';
        const ISO_8859_8        = 'ISO-8859-8';
        const ISO_8859_9        = 'ISO-8859-9';
        const ISO_8859_10       = 'ISO-8859-10';
        const ISO_8859_13       = 'ISO-8859-13';
        const ISO_8859_14       = 'ISO-8859-14';
        const ISO_8859_15       = 'ISO-8859-15';
        const ISO_8859_16       = 'ISO-8859-16';
        const EUC_CN            = 'EUC-CN';
        const CP936             = 'CP936';
        const HZ                = 'HZ';
        const EUC_TW            = 'EUC-TW';
        const BIG_5             = 'BIG-5';
        const EUC_KR            = 'EUC-KR';
        const UHC               = 'UHC';
        const ISO_2022_KR       = 'ISO-2022-KR';
        const WINDOWS_1251      = 'Windows-1251';
        const CP866             = 'CP866';
        const KOI8_R            = 'KOI8-R';
        const KOI8_U            = 'KOI8-U';
        const ARMSCII_8         = 'ArmSCII-8';
        const CP850             = 'CP850';
    }
}