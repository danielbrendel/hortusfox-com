<?php

/**
 * This class extends the base model class and represents your associated table
 */ 
class PhotoModel extends \Asatru\Database\Model {
    const FILE_IDENT = 'hortusfox_photo';

    /**
     * @param $title
     * @param $workspace
     * @param $public
     * @param $description
     * @param $keywords
     * @return array
     * @throws \Exception
     */
    public static function store($title, $workspace, $public, $description, $keywords)
    {
        try {
            if ((!isset($_FILES[self::FILE_IDENT])) || ($_FILES[self::FILE_IDENT]['error'] !== UPLOAD_ERR_OK)) {
                throw new \Exception('Errorneous file: ' . $_FILES[self::FILE_IDENT]['error']);
            }

            $file_ext = ImageModule::getImageExt($_FILES[self::FILE_IDENT]['tmp_name']);

            if ($file_ext === null) {
                throw new \Exception('File is not a valid image');
            }

            $ident = hash('crc32b', random_bytes(55) . date('Y-m-d H:i:s'));
            $slug = hash('crc32b', random_bytes(55) . date('Y-m-d H:i:s'));

            $file_name = md5(random_bytes(55) . date('Y-m-d H:i:s'));

            move_uploaded_file($_FILES[self::FILE_IDENT]['tmp_name'], public_path('/img/photos/' . $file_name . '.' . $file_ext));

            if (!ImageModule::createThumbFile(public_path('/img/photos/' . $file_name . '.' . $file_ext), ImageModule::getImageType($file_ext, public_path('/img/photos/' . $file_name)), public_path('/img/photos/' . $file_name), $file_ext)) {
                throw new \Exception('createThumbFile failed');
            }

            $keywords = trim(strtolower($keywords));

            static::raw('INSERT INTO `@THIS` (title, workspace, ident, slug, thumb, full, public, description, keywords) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?)', [
                trim($title), $workspace, $ident, $slug, $file_name . '_thumb.' . $file_ext, $file_name . '.' . $file_ext, $public, trim($description), $keywords
            ]);

            return [
                'ident' => $ident,
                'url' => url('/p/' . $slug),
                'asset' => asset('img/photos/' . $file_name . '_thumb.' . $file_ext),
                'public' => $public
            ];
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $ident
     * @return void
     * @throws \Exception
     */
    public static function rem($ident)
    {
        try {
            $item = static::where('ident', '=', $ident)->first();
            if (!$item) {
                throw new \Exception('Item not found: ' . $ident);
            }

            if (file_exists(public_path() . '/img/photos/' . $item->get('thumb'))) {
                unlink(public_path() . '/img/photos/' . $item->get('thumb'));
            }

            if (file_exists(public_path() . '/img/photos/' . $item->get('full'))) {
                unlink(public_path() . '/img/photos/' . $item->get('full'));
            }
            
            static::raw('DELETE FROM `@THIS` WHERE id = ?', [$item->get('id')]);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $slug
     * @return mixed
     * @throws \Exception
     */
    public static function getPhoto($slug)
    {
        try {
            return static::where('slug', '=', $slug)->first();
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $paginate
     * @param $tag
     * @return mixed
     * @throws \Exception
     */
    public static function fetchPublicContent($paginate = null, $tag = null)
    {
        try {
            $limit = env('APP_PHOTOS_PACKCOUNT', 12);

            if ($paginate === null) {
                if ($tag === null) {
                    return static::raw('SELECT * FROM `@THIS` WHERE public = 1 ORDER BY id DESC LIMIT ' . $limit);
                } else {
                    return static::raw('SELECT * FROM `@THIS` WHERE public = 1 AND keywords LIKE ? ORDER BY id DESC LIMIT ' . $limit, ['%' . $tag . '%']);
                }
            } else {
                if ($tag === null) {
                    return static::raw('SELECT * FROM `@THIS` WHERE public = 1 AND id < ? ORDER BY id DESC LIMIT ' . $limit, [$paginate]);
                } else {
                    return static::raw('SELECT * FROM `@THIS` WHERE public = 1 AND id < ? AND keywords LIKE ? ORDER BY id DESC LIMIT ' . $limit, [$paginate, '%' . $tag . '%']);
                }
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $tag
     * @param $public
     * @return int
     * @throws \Exception
     */
    public static function getFirstItemId($tag = null, $public = true)
    {
        try {
            $query = static::where('public', '=', $public);
            
            if ((is_string($tag)) && (strlen($tag) > 0)) {
                $query = $query->where('keywords', 'LIKE', '%' . $tag . '%');
            }

            return $query->first()->get('id');
        } catch (\Exception $e) {
            return 0;
        }
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public static function getRandomPublicPhoto()
    {
        try {
            return static::raw('SELECT * FROM `@THIS` WHERE public = 1 ORDER BY RAND() LIMIT 1')->first();
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public static function getLatestPublicPhoto()
    {
        try {
            return static::raw('SELECT * FROM `@THIS` WHERE public = 1 ORDER BY id DESC LIMIT 1')->first();
        } catch (\Exception $e) {
            throw $e;
        }
    }
}