<?php

/*
    Asatru PHP - Example controller

    Add here all your needed routes implementations related to 'index'.
*/

/**
 * Example index controller
 */
class IndexController extends BaseController {
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
	 * Handles URL: /
	 * 
	 * @param Asatru\Controller\ControllerArg $request
	 * @return Asatru\View\ViewHandler
	 */
	public function index($request)
	{
		//Generate and return a view by using the helper
		return parent::view(['content', 'index'], [
			'show_header' => true,
			'downloads' => config('downloads')
		]);
	}

	/**
	 * Handles URL: /screenshots
	 * 
	 * @param Asatru\Controller\ControllerArg $request
	 * @return Asatru\View\ViewHandler
	 */
	public function screenshots($request)
	{
		//Generate and return a view by using the helper
		return parent::view(['content', 'screenshots'], [
			'_meta_title' => 'Screenshots'
		]);
	}

	/**
	 * Handles URL: /faq
	 * 
	 * @param Asatru\Controller\ControllerArg $request
	 * @return Asatru\View\ViewHandler
	 */
	public function faq($request)
	{
		//Generate and return a view by using the helper
		return parent::view(['content', 'faq'], [
			'faqs' => config('faq'),
			'_meta_title' => 'Frequently Asked Questions'
		]);
	}
}
