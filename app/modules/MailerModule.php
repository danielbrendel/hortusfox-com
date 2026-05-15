<?php

/**
 * This class represents your module
 */
class MailerModule {
    /**
     * @var array
     */
    private static $supported_drivers = ['smtp', 'mailgun'];

    /**
     * @param $to
     * @param $subject
     * @param $message
     * @return void
     * @throws \Exception
     */
    public static function send($to, $subject, $message)
    {
        $driver = env('APP_MAILDRIVER');

        if (!in_array($driver, MailerModule::$supported_drivers)) {
            throw new \Exception('Unsupported mail driver: ' . $driver);
        }

        if (!method_exists(__CLASS__, $driver)) {
            throw new \Exception('Missing method for driver: ' . $driver);
        }

        static::$driver($to, $subject, $message);
    }

    /**
     * @param $to
     * @param $subject
     * @param $message
     * @return void
     * @throws \Exception
     */
    private static function smtp($to, $subject, $message)
    {
        try {
            $mail = new Asatru\SMTPMailer\SMTPMailer();
            $mail->setRecipient($to);
            $mail->setSubject($subject);
            $mail->setMessage($message);
            $mail->setProperties(mail_properties());
            $mail->send();
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $to
     * @param $subject
     * @param $message
     * @return void
     * @throws \Exception
     */
    private static function mailgun($to, $subject, $message)
    {
        try {
            $ch = curl_init();

            $data = [
                'from' => env('MAILGUN_FROM'),
                'to' => $to,
                'subject' => $subject,
                'html' => $message
            ];

            curl_setopt($ch, CURLOPT_URL, env('MAILGUN_BACKEND'));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_USERPWD, 'api:'. env('MAILGUN_APIKEY'));
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

            $response = curl_exec($ch);

            $curlerr = curl_error($ch);
            if ((is_string($curlerr)) && (strlen($curlerr) > 0)) {
                throw new \Exception($curlerr, curl_errno($ch));
            }

            curl_close($ch);

            $data = json_decode($response);
            if (!isset($data->id)) {
                throw new \Exception('Request failed.');
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
    