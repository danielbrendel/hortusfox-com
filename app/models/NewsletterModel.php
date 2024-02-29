<?php

/**
 * This class extends the base model class and represents your associated table
 */ 
class NewsletterModel extends \Asatru\Database\Model {
    /**
     * @param $email
     * @return void
     * @throws \Exception
     */
    public static function subscribe($email)
    {
        try {
            $count = static::where('email', '=', $email)->count()->get();
            if ($count == 0) {
                static::raw('INSERT INTO `@THIS` (email) VALUES(?)', [$email]);
            } else {
                throw new \Exception('You have already subscribed to our newsletter!');
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }
}