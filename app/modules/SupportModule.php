<?php

/**
 * This class represents your module
 */
class SupportModule {
    const TICKET_PRIORITY_LOW = 1;
    const TICKET_PRIORITY_MEDIUM = 2;
    const TICKET_PRIORITY_HIGH = 3;

    /**
     * @param $name
     * @param $email
     * @param $subject
     * @param $message
     * @return void
     * @throws \Exception
     */
    public static function request($name, $email, $subject, $message)
    {
        try {
            $ch = curl_init();

            $data = [
                'apitoken' => env('HELPREALM_RESTAPI_TOKEN'),
                'name' => $name,
                'email' => $email,
                'subject' => $subject,
                'text' => $message,
                'type' => env('HELPREALM_TICKET_TYPE'),
                'prio' => SupportModule::TICKET_PRIORITY_LOW
            ];

            curl_setopt($ch, CURLOPT_URL, env('HELPREALM_URL') . '/api/' . env('HELPREALM_WORKSPACE') . '/ticket/create');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

            $response = curl_exec($ch);

            $curlerr = curl_error($ch);
            if ((is_string($curlerr)) && (strlen($curlerr) > 0)) {
                throw new \Exception($curlerr, curl_errno($ch));
            }

            curl_close($ch);

            $data = json_decode($response);
            if ((!isset($data->code)) || ($data->code != 201)) {
                throw new \Exception('Request failed.');
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
