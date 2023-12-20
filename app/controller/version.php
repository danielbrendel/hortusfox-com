<?php

/**
 * This class represents your controller
 */
class VersionController extends BaseController {
    /**
	 * Perform base initialization
	 * 
	 * @return void
	 */
	public function __construct()
	{
	}

    /**
	 * Handles URL: /software/version
	 * 
	 * @param Asatru\Controller\ControllerArg $request
	 * @return Asatru\View\JsonHandler
	 */
    public function get_version($request)
    {
        try {
            $version = config('version');

            return json([
                'code' => 200,
                'version' => $version
            ]);
        } catch (\Exception $e) {
            return json([
                'code' => 500,
                'msg' => $e->getMessage()
            ]);
        }
    }
}
