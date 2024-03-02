<?php

/**
 * This class extends the base model class and represents your associated table
 */ 
class FaqModel extends \Asatru\Database\Model {
    /**
     * @param $limit
     * @return mixed
     * @throws \Exception
     */
    public static function getEntries($limit = 0)
    {
        try {
            if ($limit > 0) {
                return static::raw('SELECT * FROM `@THIS` WHERE active = 1 LIMIT ' . $limit);
            } else {
                return static::raw('SELECT * FROM `@THIS` WHERE active = 1');
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }
}