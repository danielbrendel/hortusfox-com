<?php

/**
 * This class extends the base model class and represents your associated table
 */ 
class DownloadsModel extends \Asatru\Database\Model {
    /**
     * @return mixed
     * @throws \Exception
     */
    public static function getDownloads($limit = 0)
    {
        try {
            if ($limit > 0) {
                return static::raw('SELECT * FROM `@THIS` WHERE active = 1 ORDER BY `version` DESC LIMIT ' . $limit);
            } else {
                return static::raw('SELECT * FROM `@THIS` WHERE active = 1 ORDER BY `version` DESC');
            } 
        } catch (\Exception $e) {
            throw $e;
        }
    }
}