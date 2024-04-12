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