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
			'show_header' => false
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
            $first = PhotoModel::getFirstItemId();

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
}
