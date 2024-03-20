<?php

/*
    Asatru PHP - routes configuration file

    Add here all your needed routes.

    Schema:
        [<url>, <method>, controller_file@controller_method]
    Example:
        [/my/route, get, mycontroller@index]
        [/my/route/with/{param1}/and/{param2}, get, mycontroller@another]
    Explanation:
        Will call index() in app\controller\mycontroller.php if request is 'get'
        Every route with $ prefix is a special route
*/

return [
    array('/', 'GET', 'index@index'),
    array('/screenshots', 'GET', 'index@screenshots'),
    array('/themes', 'GET', 'index@themes'),
    array('/tutorials', 'GET', 'index@tutorials'),
    array('/faq', 'GET', 'index@faq'),
    array('/demo', 'GET', 'index@demo'),
    array('/software/version', 'GET', 'version@get_version'),
    array('/api/photo/share', 'POST', 'api@share_photo'),
    array('/api/photo/remove', 'ANY', 'api@remove_photo'),
    array('/p/{slug}', 'GET', 'api@get_photo'),
    array('/newsletter/subscribe', 'POST', 'newsletter@subscribe'),
    array('/newsletter/unsubscribe/{token}', 'GET', 'newsletter@unsubscribe'),
    array('/sitemap', 'GET', 'index@sitemap'),
    array('$404', 'ANY', 'error404@index')
];
