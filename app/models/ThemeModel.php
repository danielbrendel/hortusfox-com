<?php

/**
 * This class extends the base model class and represents your associated table
 */ 
class ThemeModel extends \Asatru\Database\Model {
    /**
     * @return mixed
     * @throws \Exception
     */
    public static function getThemes()
    {
        try {
            return static::raw('SELECT * FROM `@THIS` WHERE active = 1');
        } catch (\Exception $e) {
            throw $e;
        }
    }
}