<?php

/*
    Asatru PHP - Model
*/

/**
 * This class extends the base model class and represents your associated table
 */ 
class AffiliateModel extends \Asatru\Database\Model {
    /**
     * @return mixed
     * @throws \Exception
     */
    public static function getList()
    {
        try {
            return static::where('active', '=', true)->get();
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @return int
     * @throws \Exception
     */
    public static function getCount()
    {
        try {
            return static::raw('SELECT COUNT(*) AS count FROM `@THIS` WHERE active = 1')->first()->get('count');
        } catch (\Exception $e) {
            throw $e;
        }
    }
}