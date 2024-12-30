<?php

/**
 * This class extends the base model class and represents your associated table
 */ 
class TutorialsModel extends \Asatru\Database\Model {
    /**
     * @param $category
     * @return mixed
     * @throws \Exception
     */
    public static function getTutorials($category)
    {
        try {
            return static::raw('SELECT * FROM `@THIS` WHERE category = ?', [$category]);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public static function getAll()
    {
        try {
            return static::raw('SELECT * FROM `@THIS` ORDER BY category ASC');
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public static function getCategories()
    {
        try {
            return static::raw('SELECT DISTINCT category FROM `@THIS` ORDER BY category ASC');
        } catch (\Exception $e) {
            throw $e;
        }
    }
}