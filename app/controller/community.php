<?php

/**
 * This class represents your controller
 */
class CommunityController extends BaseController {
    const INDEX_LAYOUT = 'layout';

	/**
	 * Perform base initialization
	 * 
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct(self::INDEX_LAYOUT);
	}

    /**
	 * Handles URL: /community
	 * 
	 * @param Asatru\Controller\ControllerArg $request
	 * @return Asatru\View\ViewHandler
	 */
	public function index($request)
	{
		return parent::view(['content', 'community'], [
			'show_header' => false,
			'_meta_title' => 'Community Photos',
			'_meta_description' => 'View plant photos from the workspaces of our community',
			'_meta_url' => url('/community'),
		]);
	}

    /**
	 * Handles URL: /community/fetch
	 * 
	 * @param Asatru\Controller\ControllerArg $request
	 * @return Asatru\View\JsonHandler
	 */
	public function fetch($request)
	{
		try {
            $paginate = $request->params()->query('paginate', null);
            $tag = $request->params()->query('tag', null);

            $data = PhotoModel::fetchPublicContent($paginate, $tag)->asArray();
            $first = PhotoModel::getFirstItemId($tag);

            foreach ($data as $key => &$item) {
                if ((is_string($item['keywords'])) && (strlen($item['keywords']) > 0)) {
                    $item['keywords'] = explode(' ', $item['keywords']);
                }
            }

            return json([
                'code' => 200,
                'data' => $data,
                'first' => $first
            ]);
        } catch (\Exception $e) {
            return json([
                'code' => 500,
                'msg' => $e->getMessage()
            ]);
        }
	}

	/**
	 * Handles URL: /community/fetch/random
	 * 
	 * @param Asatru\Controller\ControllerArg $request
	 * @return Asatru\View\JsonHandler
	 */
	public function random($request)
	{
		try {
            $random_item = PhotoModel::getRandomPublicPhoto();

			header('Access-Control-Allow-Origin: *');

            return json([
                'code' => 200,
                'data' => $random_item?->asArray()
            ]);
        } catch (\Exception $e) {
            return json([
                'code' => 500,
                'msg' => $e->getMessage()
            ]);
        }
	}

	/**
	 * Handles URL: /community/fetch/latest
	 * 
	 * @param Asatru\Controller\ControllerArg $request
	 * @return Asatru\View\JsonHandler
	 */
	public function latest($request)
	{
		try {
            $latest_item = PhotoModel::getLatestPublicPhoto();

			header('Access-Control-Allow-Origin: *');

            return json([
                'code' => 200,
                'data' => $latest_item?->asArray()
            ]);
        } catch (\Exception $e) {
            return json([
                'code' => 500,
                'msg' => $e->getMessage()
            ]);
        }
	}
}
