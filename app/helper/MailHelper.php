<?php

/**
 * @return array
 */
function mail_properties()
{
    $result = [];

    if ($_ENV['SMTP_ENCRYPTION'] === 'none') {
        $_ENV['SMTP_ENCRYPTION'] = 'tls';

        $result = [
            'SMTPSecure' => false,
            'SMTPAutoTLS' => false
        ];
    }

    return $result;
}