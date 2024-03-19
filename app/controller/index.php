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
			'downloads' => DownloadsModel::getDownloads()
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
	 * Handles URL: /themes
	 * 
	 * @param Asatru\Controller\ControllerArg $request
	 * @return Asatru\View\ViewHandler|Asatru\View\RedirectHandler
	 */
	public function themes($request)
	{
		if (!env('APP_ENABLETHEMES')) {
			return redirect('/');
		}

		return parent::view(['content', 'themes'], [
			'_meta_title' => 'Themes - The extra spice for your workspace',
			'themes' => ThemeModel::getThemes()
		]);
	}

	/**
	 * Handles URL: /tutorials
	 * 
	 * @param Asatru\Controller\ControllerArg $request
	 * @return Asatru\View\ViewHandler
	 */
	public function tutorials($request)
	{
		$videos = [];
		$videos['install'] = TutorialsModel::getTutorials('install');
		$videos['usage'] = TutorialsModel::getTutorials('usage');

		//Generate and return a view by using the helper
		return parent::view(['content', 'tutorials'], [
			'_meta_title' => 'Tutorials',
			'videos' => $videos
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
			'faqs' => FaqModel::getEntries(),
			'_meta_title' => 'Frequently Asked Questions'
		]);
	}

	/**
	 * Handles URL: /demo
	 * 
	 * @param Asatru\Controller\ControllerArg $request
	 * @return Asatru\View\ViewHandler|Asatru\View\RedirectHandler
	 */
	public function demo($request)
	{
		if (!env('DEMO_ENABLE')) {
			return redirect('/');
		}

		$accounts = [];

		for ($i = 0; $i < 10; $i++) {
			$accounts[] = [
				'email' => 'test' . strval($i + 1) . '@gmail.com',
				'password' => 'test'
			];
		}

		//Generate and return a view by using the helper
		return parent::view(['content', 'demo'], [
			'_meta_title' => 'Live Demo',
			'accounts' => $accounts
		]);
	}

	/**
	 * Handles URL: /sitemap
	 * 
	 * @param Asatru\Controller\ControllerArg $request
	 * @return mixed
	 */
	public function sitemap($request)
	{
		try {
			$sitemap = SitemapModule::generate();
			
			header('Content-Type: text/xml');
			echo $sitemap;

			exit(0);
		} catch (\Exception $e) {
			return abort(500);
		}
	}
}
