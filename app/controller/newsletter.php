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

            $data = NewsletterModel::subscribe($email);

            MailerModule::send($email, 'Welcome to the HortusFox newsletter', view('mail/newsletter_subscribe', [], ['token' => $data['token'], 'confirmation' => $data['confirmation']])->out(true));
            FlashMessage::setMsg('success', 'You have successfully subscribed to our newsletter!');

            return redirect('/#info');
        } catch (\Exception $e) {
            FlashMessage::setMsg('error', $e->getMessage());
            return redirect('/#info');
        }
    }

    /**
	 * Handles URL: /newsletter/confirm
	 * 
	 * @param Asatru\Controller\ControllerArg $request
	 * @return Asatru\View\RedirectHandler
	 */
    public function confirm($request)
    {
        try {
            $confirm_token = $request->params()->query('confirm_token', null);

            $data = NewsletterModel::confirm($confirm_token);

            FlashMessage::setMsg('success', 'You have successfully verified your e-mail address. Have fun with our newsletter!');

            return redirect('/#info');
        } catch (\Exception $e) {
            FlashMessage::setMsg('error', $e->getMessage());
            return redirect('/#info');
        }
    }

    /**
	 * Handles URL: /newsletter/unsubscribe/{token}
	 * 
	 * @param Asatru\Controller\ControllerArg $request
	 * @return Asatru\View\RedirectHandler
	 */
    public function unsubscribe($request)
    {
        try {
            $token = $request->arg('token', null);

            NewsletterModel::unsubscribe($token);

            FlashMessage::setMsg('success', 'You have successfully unsubscribed from our newsletter. Feel free to resubscribe at any time!');

            return redirect('/#info');
        } catch (\Exception $e) {
            FlashMessage::setMsg('error', $e->getMessage());
            return redirect('/#info');
        }
    }
}
