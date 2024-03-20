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

            $token = NewsletterModel::subscribe($email);

            $mail = new Asatru\SMTPMailer\SMTPMailer();
            $mail->setRecipient($email);
            $mail->setSubject('Welcome to the HortusFox newsletter');
            $mail->setView('mail/newsletter_subscribe', [], ['token' => $token]);
            $mail->setProperties(mail_properties());
            $mail->send();

            FlashMessage::setMsg('success', 'You have successfully subscribed to our newsletter!');

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
