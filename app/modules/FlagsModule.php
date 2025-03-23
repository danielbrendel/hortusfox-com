<?php

/**
 * This class represents your module
 */
class FlagsModule {
    /**
     * @return array
     */
    public static function get()
    {
        $result = [];

        $flags = scandir(public_path() . '/img/flags');
        foreach ($flags as $flag) {
            if (substr($flag, 0, 1) !== '.') {
                $result[] = [
                    'ident' => explode('.', $flag)[0],
                    'asset' => asset('img/flags/' . $flag)
                ];
            }
        }
        
        return $result;
    }
}
