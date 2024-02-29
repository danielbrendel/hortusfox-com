<?php

/**
 * This class represents your controller
 */
class NewsletterController extends BaseController {
    /**
	 * Handles URL: /newsletter/subscribe
	 * 
	 * @param Asatru\Controller\ControllerArg $request
	 * @return Asatru\View\RedirectHandler
	 */
    public function subscribe($request)
    {
        try {
            $email = $request->params()->query('email', null);

            NewsletterModel::subscribe($email);

            FlashMessage::setMsg('success', 'You have successfully subscribed to our newsletter!');

            return redirect('/#info');
        } catch (\Exception $e) {
            FlashMessage::setMsg('error', $e->getMessage());
            return redirect('/#info');
        }
    }
}
