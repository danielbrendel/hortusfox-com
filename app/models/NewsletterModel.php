<?php

/**
 * This class extends the base model class and represents your associated table
 */ 
class NewsletterModel extends \Asatru\Database\Model {
    const EMAIL_CONFIRMED = '_confirmed';
    const EMAIL_DEFCONFTIME = 72;
    const EMAIL_DEFCONFLIMIT = 100;

    /**
     * @param $email
     * @return array
     * @throws \Exception
     */
    public static function subscribe($email)
    {
        try {
            $count = static::where('email', '=', $email)->count()->get();
            if ($count != 0) {
                throw new \Exception('You have already subscribed to our newsletter!');
            }
            
            $confirmation = md5(random_bytes(55) . date('Y-m-d H:i:s') . $email);
            $token = md5(random_bytes(55) . date('Y-m-d H:i:s') . $email);

            static::raw('INSERT INTO `@THIS` (email, confirmation, token) VALUES(?, ?, ?)', [$email, $confirmation, $token]);

            return ['confirmation' => $confirmation, 'token' => $token];
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $confirmation
     * @return void
     * @throws \Exception
     */
    public static function confirm($confirmation)
    {
        try {
            $item = static::raw('SELECT * FROM `@THIS` WHERE confirmation = ?', [$confirmation])->first();
            if (!$item) {
                throw new \Exception('Invalid confirmation token specified.');
            }

            static::raw('UPDATE `@THIS` SET confirmation = ? WHERE id = ?', [self::EMAIL_CONFIRMED, $item->get('id')]);
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
     * @param $limit
     * @return mixed
     * @throws \Exception
     */
    public static function getProcessUsers($process, $limit = 5)
    {
        try {
            return static::raw('SELECT * FROM `@THIS` WHERE confirmation = ? AND (process <> ? OR process IS NULL) LIMIT ' . $limit, [self::EMAIL_CONFIRMED, $process]);
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

    /**
     * @return void
     * @throws \Exception
     */
    public static function cleanUnconfirmed()
    {
        try {
            $hours = env('APP_NEWSLETTERCONFTIME', self::EMAIL_DEFCONFTIME);
            $limit = env('APP_NEWSLETTERCONFLIMIT', self::EMAIL_DEFCONFLIMIT);

            $unconfirmed_subscribers = static::raw('SELECT * FROM `@THIS` WHERE confirmation <> ? AND created_at <= NOW() - INTERVAL ' . $hours . ' HOUR LIMIT ' . $limit, [self::EMAIL_CONFIRMED]);
            
            foreach ($unconfirmed_subscribers as $subscriber) {
                static::raw('DELETE FROM `@THIS` WHERE id = ?', [$subscriber->get('id')]);
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @return int
     * @throws \Exception
     */
    public static function getSubscriberCount()
    {
        try {
            return static::raw('SELECT COUNT(*) AS `count` FROM `@THIS` WHERE confirmation = ?', [self::EMAIL_CONFIRMED])->first()->get('count');
        } catch (\Exception $e) {
            throw $e;
        }
    }
}