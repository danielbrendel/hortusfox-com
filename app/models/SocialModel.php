<?php

/**
 * This class extends the base model class and represents your associated table
 */ 
class SocialModel extends \Asatru\Database\Model {
    const FILE_IDENT = 'asset';

    /**
     * @param $content
     * @return void
     * @throws \Exception
     */
    public static function addPost($content)
    {
        try {
            static::raw('INSERT INTO `@THIS` (content, asset) VALUES(?, NULL)', [$content]);

            $item = static::raw('SELECT * FROM `@THIS` ORDER BY id DESC LIMIT 1')->first();

            if ((isset($_FILES[self::FILE_IDENT])) && ($_FILES[self::FILE_IDENT]['error'] === UPLOAD_ERR_OK)) {
                $file_ext = ImageModule::getImageExt($_FILES[self::FILE_IDENT]['tmp_name']);

                if ($file_ext === null) {
                    throw new \Exception('File is not a valid image');
                }

                $file_name = md5(random_bytes(55) . date('Y-m-d H:i:s'));

                move_uploaded_file($_FILES[self::FILE_IDENT]['tmp_name'], public_path('/img/social/' . $file_name . '.' . $file_ext));

                static::raw('UPDATE `@THIS` SET asset = ? WHERE id = ?', [$file_name . '.' . $file_ext, $item->get('id')]);
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @return void
     * @throws \Exception
     */
    public static function publishPost()
    {
        try {
            $server_instance = env('MASTODON_SERVER_INSTANCE');
            $access_token = env('MASTODON_ACCESS_TOKEN');

            $item = static::raw('SELECT * FROM `@THIS` WHERE posted = 0 ORDER BY id ASC LIMIT 1')->first();
            if (!$item) {
                return;
            }

            $media_id = null;

            if (($item->get('asset')) && (is_file(public_path() . '/img/social/' . $item->get('asset')))) {
                $response = NetUtilsModule::remoteRequest($server_instance . '/api/v2/media', [
                    'header' => [
                        'Authorization: Bearer ' . $access_token,
                        'Content-Type: multipart/form-data'
                    ],
                    'post' => [
                        'file' => new \CURLFile(public_path() . '/img/social/' . $item->get('asset'))
                    ]
                ]);
                
                $media_json = json_decode($response['data']);
                if (isset($media_json->error)) {
                    throw new \Exception('[api/v2/media] ' . $media_json->error, $response['info']['http_code']);
                }

                $media_id = $media_json->id;
            }

            $post_data = [
                'status' => $item->get('content'),
                'visibility' => 'public'
            ];

            if ($media_id) {
                $post_data['media_ids'] = [$media_id];
            }

            $response = NetUtilsModule::remoteRequest($server_instance . '/api/v1/statuses', [
                'header' => [
                    'Authorization: Bearer ' . $access_token,
                    'Content-Type: application/json'
                ],
                'post' => json_encode($post_data)
            ]);

            $status_json = json_decode($response['data']);
            if (isset($status_json->error)) {
                throw new \Exception('[api/v1/statuses] ' . $status_json->error, $response['info']['http_code']);
            }

            static::raw('UPDATE `@THIS` SET posted = 1 WHERE id = ?', [$item->get('id')]);
        } catch (\Exception $e) {
            throw $e;
        }
    }
}