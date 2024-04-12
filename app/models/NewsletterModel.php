<?php

/**
 * This class extends the base model class and represents your associated table
 */ 
class NewsletterModel extends \Asatru\Database\Model {
    /**
     * @param $email
     * @return string
     * @throws \Exception
     */
    public static function subscribe($email)
    {
        try {
            $count = static::where('email', '=', $email)->count()->get();
            if ($count != 0) {
                throw new \Exception('You have already subscribed to our newsletter!');
            }

            $token = md5(random_bytes(55) . date('Y-m-d H:i:s') . $email);

            static::raw('INSERT INTO `@THIS` (email, token) VALUES(?, ?)', [$email, $token]);

            return $token;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $token
     * @return void
     * @throws \Exception
     */
    public static function unsubscribe($token)
    {
        try {
            $item = static::raw('SELECT * FROM `@THIS` WHERE token = ?', [$token])->first();
            if (!$item) {
                throw new \Exception('Subscription not found.');
            }

            static::raw('DELETE FROM `@THIS` WHERE token = ?', [$token]);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $process
     * @return mixed
     * @throws \Exception
     */
    public static function getProcessUsers($process, $limit = 5)
    {
        try {
            return static::raw('SELECT * FROM `@THIS` WHERE process <> ? OR process IS NULL LIMIT ' . $limit, [$process]);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $id
     * @param $process
     * @return void
     * @throws \Exception
     */
    public static function updateUserProcess($id, $process)
    {
        try {
            static::raw('UPDATE `@THIS` SET process = ? WHERE id = ?', [$process, $id]);
        } catch (\Exception $e) {
            throw $e;
        }
    }
}