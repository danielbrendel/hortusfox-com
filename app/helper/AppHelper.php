<?php

/**
 * @param $item
 * @param $fallback
 * @return mixed
 */
function app($item, $fallback = null)
{
    try {
        return AppModel::querySetting($item);
    } catch (\Exception $e) {
        return $fallback;
    }
}

/**
 * @param $captcha
 * @return void
 */
function setGlobalCaptcha($captcha)
{
    global $global_captcha;
    $global_captcha = $captcha;
}

/**
 * @return array
 */
function getGlobalCaptcha()
{
    global $global_captcha;
    return $global_captcha;
}