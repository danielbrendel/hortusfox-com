<?php

/**
 * This class extends the base model class and represents your associated table
 */ 
class AppModel extends \Asatru\Database\Model {
    /**
     * @param $id
     * @return mixed
     * @throws \Exception
     */
    public static function getSettings($id = 1)
    {
        try {
            return static::raw('SELECT * FROM `@THIS` WHERE id = ?', [$id])->first();
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $item
     * @param $id
     * @return mixed
     * @throws \Exception
     */
    public static function querySetting($item, $id = 1)
    {
        try {
            return static::raw('SELECT * FROM `@THIS` WHERE id = ?', [$id])->first()->get($item);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $item
     * @param $value
     * @param $id
     * @return void
     * @throws \Exception
     */
    public static function saveSetting($item, $value, $id = 1)
    {
        try {
            static::raw('UPDATE `@THIS` SET ' . $item . ' = ? WHERE id = ?', [$value, $id]);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $id
     * @return void
     * @throws \Exception
     */
    public static function processNewsletter($id = 1)
    {
        try {
            if (!app('newsletter_enable')) {
                throw new \Exception('Newsletter is not enabled.');
            }

            $token = static::querySetting('newsletter_token', $id);
            $subject = static::querySetting('newsletter_subject', $id);
            $content = static::querySetting('newsletter_content', $id);

            $users = NewsletterModel::getProcessUsers($token, env('APP_NEWSLETTERLIMIT'));
            foreach ($users as $user) {
                $mail = new Asatru\SMTPMailer\SMTPMailer();
                $mail->setRecipient($user->get('email'));
                $mail->setSubject($subject);
                $mail->setView('mail/newsletter_base', [], ['subject' => $subject, 'content' => $content, 'token' => $user->get('token')]);
                $mail->setProperties(mail_properties());
                $mail->send();

                NewsletterModel::updateUserProcess($user->get('id'), $token);
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }
}