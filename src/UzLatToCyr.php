<?php

namespace pdima88\uztranslit;

use Nette\Utils\Paginator;

class UzLatToCyr
{
    static protected $_r = null;

    static function init()
    {
        self::$_r = [
            'a' => 'а', 'A' => 'А',
            'b' => 'б', 'B' => 'Б',
            'v' => 'в', 'V' => 'В',
            'g' => 'г', 'G' => 'Г',
            'd' => 'д', 'D' => 'Д',
            'e' => ['е', 'э'], 'E' => ['Е', 'Э'],
            'ye' => 'е', 'Ye' => 'Е',  'YE' => 'Е',
            'yo' => 'ё', 'Yo' => 'Ё',  'YO' => 'Ё',
            'j' => 'ж', 'J' => 'Ж',
            'z' => 'з', 'Z' => 'З',
            'i' => 'и', 'I' => 'И',
            'y' => 'й', 'Y' => 'Й',
            'k' => 'к', 'K' => 'К',
            'l' => 'л', 'L' => 'Л',
            'm' => 'м', 'M' => 'М',
            'n' => 'н', 'N' => 'Н',
            'o' => 'о', 'O' => 'О',
            'p' => 'п', 'P' => 'П',
            'r' => 'р', 'R' => 'Р',
            's' => 'с', 'S' => 'С',
            't' => 'т', 'T' => 'Т',
            'u' => 'у', 'U' => 'У',
            'f' => 'ф', 'F' => 'Ф',
            'x' => 'x', 'X' => 'Х',
            'ts' => 'ц', 'Ts' => 'Ц', 'TS' => 'Ц',
            'ch' => 'ч', 'Ch' => 'Ч', 'CH' => 'Ч',
            'sh' => 'ш', 'Sh' => 'Ш', 'SH' => 'Ш',
            'shch' => 'щ', 'Shch' => 'Щ', 'SHCH' => 'Щ',
            '\'' => 'ъ',
            'yu' => 'ю', 'Yu' => 'Ю', 'YU' => 'Ю',
            'ya' => 'я', 'Ya' => 'Я', 'YA' => 'Я',
            'o\'' => 'ў', 'O\'' => 'Ў',
            'q' => 'қ', 'Q' => 'Қ',
            'g\'' => 'ғ', 'G\'' => 'Ғ',
            'h' => 'ҳ', 'H' => 'Ҳ'
        ];
    }

    const APOSTROPHES = '’ʻ\'`‘';
    const VOWELS = 'aeoiu';
    const CONSONANTS = 'bvgdjzyklmnprstfxhq';

    /*static protected function _translitWord($s) {
        $res = '';
        $prev = '';
        $strLen = Strings::length($s);
        $isUzbWord = false;

        for ($i = 0; $i < $strLen; $i++) {
            $c = Strings::substring($s, $i, 1);
            if (Strings::indexOf(self::APOSTROPHES, $c)) {
                if ($prev == 'g' || $prev == 'G' || $prev == 'o' || $prev == 'O') {
                    $isUzbWord = true;
                }
            } elseif ($c == 'q' || $c == 'Q' || $c == 'h' || $c == 'H') {
                //$isUzbWord = true;
            }
            $prev = $c;
        }

        $prev = '';
        for ($i = 0; $i < $strLen; $i++) {
            $c4 = Strings::substring($s, $i, 4);
            $c3 = Strings::substring($c4, 1, 3);
            $c2 = Strings::substring($c3, 1, 2);
            $c1 = Strings::substring($c2, 1, 1);

            if ((Strings::lower($c4) == 'shch') && isset(self::$_r[$c4])) {
                if ($prev == '' || //начало слова
                    $prev == 'e' || $prev == 'E' || // перед Е
                    $i+4 == $strLen-1) { // в конце слова
                    $r = self::$_r[$c4];
                    $i+=3;
                }
            }

            $next = (($i+1 < $strLen) ? Strings::substring($s, $i+1, 1) : '');
            $after = (($i+2 < $strLen) ?  Strings::substring($s, $i+2, 1) : '');
            $lchar = Strings::lower($char);
            $lnext =

            if ($lchar == 'y' ) {

            }
            if (Strings::indexOf("abcdefghijklmnopqrstuvwxyzабвгдеёжзийклмнпорстуфхцчшщъыьэюяўқғҳ'`ʻ", $lchar) >= 0) {
                $word .= $char;
            } else {
                uzToCyrilWord($word);
            }
        }
    }*/

    private static function translitWord($s, &$res)
    {
        $success = true;
        $res = '';
        $prev = '';
        $str = preg_split( '//u', $s, null, PREG_SPLIT_NO_EMPTY);
        $strLen = count($str);
        for ($i = 0; $i < $strLen; $i++) {
            $c = $str[$i];
            $c2 = ($str[$i+1] ?? '');if (!empty($c2) && mb_strpos(self::APOSTROPHES,$c2)!== false) $c2 = '\'';
            $c3 = ($str[$i+2] ?? '');if (!empty($c3) && mb_strpos(self::APOSTROPHES,$c3)!== false) $c3 = '\'';
            $c4 = ($str[$i+3] ?? '');
            $s2 = $c.$c2;
            $s4 = $s2.$c3.$c4;

            if ($c == 'w' || $c == 'W') {
                $success = false;
            }
            if (($c == 'c' || $c == 'C') && ($c2 != 'h' && $c2 != 'H')) {
                $success = false;
            }

            if (mb_strtolower($c.$c2.$c3) == 'yo\'') $s2 = '';

            if (mb_strtolower($s4) == 'siya' && mb_strpos(self::CONSONANTS, $prev) !== false) {
                if ($c == 's') $s2 = 'ts';
                if ($c == 'S') $s2 = 'TS';
                $i--;
            }

            if (mb_strlen($s4) == 4 && isset(self::$_r[$s4])) {
                $res .= self::$_r[$s4];
                $prev = mb_strtolower($c);
                $i+=3;
                continue;
            } elseif (mb_strlen($s2) == 2 && isset(self::$_r[$s2])) {
                $res .= self::$_r[$s2];
                if (mb_strpos(self::APOSTROPHES, $c2) !== false) {
                    $prev = mb_strtolower($c);
                } else {
                    $prev = mb_strtolower($c2);
                }
                $i++;
                continue;
            }

            if (!isset(self::$_r[$c])) {
                $res .= $c;
            } elseif (is_array(self::$_r[$c])) {
                if ($c == 'e' || $c == 'E') {
                    if ($prev != '' && mb_strpos(self::CONSONANTS, $prev) !== false) {
                        $res .= self::$_r[$c][0];
                    } else {
                        $res .= self::$_r[$c][1];
                    }
                }
            } else {
                
                $res .= self::$_r[$c];
            }
            $prev = mb_strtolower($c);
        }

        return $success;
    }


    static function translit($s, $allWords = false) {
        if (self::$_r == null) {
            self::init();
        }
        $result = '';
        $buf = '';
        $str = preg_split( '//u', $s, null, PREG_SPLIT_NO_EMPTY);
        foreach($str as $c) {
            if (!preg_match('/[\w]/u', $c) && mb_strpos(self::APOSTROPHES, $c) === false) {
                if (!empty($buf)) {
                    $t = '';
                    if (self::translitWord($buf, $t) || $allWords) {
                        $result .= $t;
                    } else {
                        $result .= $buf;
                    }
                    $buf = '';
                }
                $result .= $c;
            } else {
                $buf .= $c;
            }
        }

        if (!empty($buf)) {
            $t = '';
            if (self::translitWord($buf, $t) || $allWords) {
                $result .= $t;
            } else {
                $result .= $buf;
            }
        }

        return $result;
    }

    static function translitHtml($html) {
        $result = '';
        $buf = '';
        $buf2 = '';

        $str = preg_split( '//u', $html, null, PREG_SPLIT_NO_EMPTY);

        $m = 0; // 0 - text, 1 - html tag, -1 - < left bracket found, 2 - &sequence, 3 - script, 4 - style

        foreach($str as $c) {
            if ($m == -1) {
                if ($c == '/') {
                    $buf2 .= $c;
                    continue;
                }
                elseif (preg_match('/[!a-zA-Z]/', $c)) {
                    $m = 1;
                    if (!empty($buf)) {
                        $result .= self::translit($buf);
                    }
                    $buf = $buf2.$c;
                    $buf2 = '';
                    continue;
                } else {
                    $buf .= $buf2;
                    $buf2 = '';
                    $m = 0;
                }
            }

            if ($m == 2) {
                if (preg_match('/[a-zA-Z0-9_]/', $c)) {
                    $buf .= $c;
                    continue;
                } else {
                    if ($c == ';') {
                        $result .= $buf.$c;
                        $buf = '';
                        $m = 0;
                        continue;
                    }
                    $m = 0;
                    $result .= $buf;
                    $buf = '';
                }
            }

            if ($m == 0) {
                // text
                if ($c == '<') {
                    $m = -1;
                    $buf2 = '<';
                } elseif ($c == '&') {
                    $m = 2;
                    if (!empty($buf)) {
                        $result .= self::translit($buf);
                    }
                    $buf = $c;
                } else {
                    $buf .= $c;
                }
            } elseif ($m == 1) {
                if ($c == '>') {
                    if (substr($buf, 0, 7) == '<script') {
                        $m = 3;
                        $buf .= $c;
                    } else if (substr($buf, 0, 6) == '<style') {
                        $m = 4;
                        $buf .= $c;
                    } else {
                        $result .= $buf . $c; // pastes html to result string as is
                        $buf = '';
                        $m = 0;
                    }
                } else {
                    $buf .= $c;
                }
            } elseif ($m == 3 || $m == 4) {
                $buf .= $c;
                $buf2 .= $c;
                if (strlen($buf2) > 8) $buf2 = substr($buf2, -8);
                if ($m == 3 && $buf2 == '</script') {
                    $result .= $buf;
                    $buf = '';
                    $m = 1;
                } elseif ($m==4 && substr($buf2,-7) == '</style') {
                    $result .= $buf;
                    $buf = '';
                    $m = 1;
                }
            }
        }
        if (!empty($buf)) {
            if ($m == 0) {
                $result .= self::translit($buf);
            } else {
                $result .= $buf.$c; // pastes html to result string as is
            }
        }

        return $result;
    }
}

