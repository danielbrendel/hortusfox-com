<?php

/**
 * This class represents your controller
 */
class ApiController extends BaseController {
    /**
	 * Perform base initialization
	 * 
	 * @return void
	 */
	public function __construct()
	{
        if (!env('APP_ENABLE_PHOTO_SHARE', false)) {
            http_response_code(403);
            header('Content-Type: application/json');
            exit(json_encode(array('code' => 403, 'msg' => 'Photo sharing is currently deactivated')));
        }
	}

    /**
	 * Handles URL: /api/photo/share
	 * 
	 * @param Asatru\Controller\ControllerArg $request
	 * @return Asatru\View\JsonHandler
	 */
    public function share_photo($request)
    {
        try {
            $title = $request->params()->query('title', 'Untitled Photo');

            $data = PhotoModel::store($title);

            return json([
                'code' => 200,
                'data' => $data
            ]);
        } catch (\Exception $e) {
            return json([
                'code' => 500,
                'msg' => $e->getMessage()
            ]);
        }
    }

    /**
	 * Handles URL: /api/photo/remove
	 * 
	 * @param Asatru\Controller\ControllerArg $request
	 * @return Asatru\View\JsonHandler
	 */
    public function remove_photo($request)
    {
        try {
            $ident = $request->params()->query('ident', null);
            $ret = $request->params()->query('ret', null);

            PhotoModel::rem($ident);

            if ((is_string($ret)) && ($ret === 'home')) {
                FlashMessage::setMsg('success', 'Your photo has been successfully removed');
                return redirect('/#info');
            }

            return json([
                'code' => 200
            ]);
        } catch (\Exception $e) {
            if ((isset($ret)) && (is_string($ret)) && ($ret === 'home')) {
                FlashMessage::setMsg('error', $e->getMessage());
                return redirect('/');
            }

            return json([
                'code' => 500,
                'msg' => $e->getMessage()
            ]);
        }
    }

    /**
	 * Handles URL: /img/{slug}
	 * 
	 * @param Asatru\Controller\ControllerArg $request
	 * @return Asatru\View\RedirectHandler
	 */
    public function get_photo($request)
    {
        try {
            $slug = $request->arg('slug');

            $item = PhotoModel::getPhoto($slug);

            return redirect('/img/photos/' . $item->get('thumb'));
        } catch (\Exception $e) {
            return redirect('/');
        }
    }
}
