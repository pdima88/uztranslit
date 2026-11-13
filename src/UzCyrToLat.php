<?php

namespace pdima88\uztranslit;

class UzCyrToLat
{
    static protected $_r = null;

    static function init()
    {
        self::$_r = [
            'а' => 'a', 'А' => 'A',
            'б' => 'b', 'Б' => 'B',
            'в' => 'v', 'В' => 'V',
            'г' => 'g', 'Г' => 'G',
            'д' => 'd', 'Д' => 'D',
            'е' => ['e', 'ye'], 'Е' => ['E', 'Ye'],
            'ё' => 'yo', 'Ё' => 'Yo',
            'ж' => 'j', 'Ж' => 'J',
            'з' => 'z', 'З' => 'Z',
            'и' => 'i', 'И' => 'I',
            'й' => 'y', 'Й' => 'Y',
            'к' => 'k', 'К' => 'K',
            'л' => 'l', 'Л' => 'L',
            'м' => 'm', 'М' => 'M',
            'н' => 'n', 'Н' => 'N',
            'о' => 'o', 'О' => 'O',
            'п' => 'p', 'П' => 'P',
            'р' => 'r', 'Р' => 'R',
            'с' => 's', 'С' => 'S',
            'т' => 't', 'Т' => 'T',
            'у' => 'u', 'У' => 'U',
            'ф' => 'f', 'Ф' => 'F',
            'х' => 'x', 'Х' => 'X',
            'ц' => ['ts', 's'], 'Ц' => ['Ts', 'S'],
            'ч' => 'ch', 'Ч' => 'Ch',
            'ш' => 'sh', 'Ш' => 'Sh',
            'щ' => 'shch', 'Щ' => 'Shch',
            'ъ' => '’', 'Ъ' => '’',
            'ы' => 'i', 'Ы' => 'I',
            'ь' => '', 'Ь' => '',
            'э' => 'e', 'Э' => 'E',
            'ю' => 'yu', 'Ю' => 'Yu',
            'я' => 'ya', 'Я' => 'Ya',
            'ў' => 'oʻ', 'Ў' => 'Oʻ',
            'қ' => 'q', 'Қ' => 'Q',
            'ғ' => 'gʻ', 'Ғ' => 'Gʻ',
            'ҳ' => 'h', 'Ҳ' => 'H'
        ];
    }

    const VOWELS = 'аеёиоуъыьэюяў';
    const CONSONANTS = 'бвгджзйклмнпрстфхцчшщқғҳ';

    static function utf8_iter_fast(string $s): \Generator {
        $len = strlen($s);

        for ($i = 0; $i < $len; ) {
            $b1 = $s[$i];

            $byte = ord($b1);

            if ($byte < 0x80) {
                yield $b1;
                $i++;
                continue;
            }

            if ($byte < 0xE0) {
                yield $b1 . $s[$i+1];
                $i += 2;
                continue;
            }

            if ($byte < 0xF0) {
                yield $b1 . $s[$i+1] . $s[$i+2];
                $i += 3;
                continue;
            }

            yield $b1 . $s[$i+1] . $s[$i+2] . $s[$i+3];
            $i += 4;
        }
    }

    /**
     * Transliterates from uzbek cyrillic to latin
     * @param string $s Source text (can be HTML also)
     * @return string Transliterated text
     */
    static function translit($s)
    {
        if (self::$_r == null) {
            self::init();
        }

        $res = '';
        $prev = '';

        foreach (self::utf8_iter_fast($s) as $c) {
            if (!isset(self::$_r[$c])) {
                $res .= $c;
            } elseif (is_array(self::$_r[$c])) {
                if ($c == 'е' || $c == 'Е') {
                    if ($prev != '' && mb_strpos(self::CONSONANTS, $prev) !== false) {
                        $res .= self::$_r[$c][0];
                    } else {
                        $res .= self::$_r[$c][1];
                    }
                } elseif ($c == 'ц' || $c == 'Ц') {
                    if ($prev != '' && mb_strpos(self::VOWELS, $prev) !== false) {
                        $res .= self::$_r[$c][0];
                    } else {
                        $res .= self::$_r[$c][1];
                    }
                }
            } else {
                if (($c == 'ҳ' || $c == 'Ҳ') && ($prev == 'с')) {
                    $res .= "’"; //s’h
                }
                $res .= self::$_r[$c];
            }
            $prev = mb_strtolower($c);
        }
        return $res;
    }
}

