<?php

/**
 * This class extends the base model class and represents your associated table
 */ 
class PhotoModel extends \Asatru\Database\Model {
    const FILE_IDENT = 'hortusfox_photo';

    /**
     * @param $title
     * @return array
     * @throws \Exception
     */
    public static function store($title)
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

            static::raw('INSERT INTO `@THIS` (title, ident, slug, thumb, full) VALUES(?, ?, ?, ?, ?)', [
                $title, $ident, $slug, $file_name . '_thumb.' . $file_ext, $file_name . '.' . $file_ext
            ]);

            return [
                'ident' => $ident,
                'url' => url('/p/' . $slug),
                'asset' => asset('img/photos/' . $file_name . '_thumb.' . $file_ext)
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
}