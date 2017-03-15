<?php

namespace Admin\Listeners;

use Admin\Entity\Activation;
use Zend\Mail;
use Zend\Mvc\MvcEvent;
use Zend\Mvc\Router\RouteStackInterface;


/**
 * Class AfterUserRegister
 * @package Admin\Listeners
 */
class AfterUserRegister
{

    /**
     * @var MvcEvent
     */
    protected $mvcEvent;
    /**
     * @var RouteStackInterface
     */
    protected $router;

    /**
     * AfterUserRegister constructor.
     * @param MvcEvent $mvcEvent
     * @param RouteStackInterface $router
     */
    public function __construct(MvcEvent $mvcEvent, RouteStackInterface $router)
    {
        $this->mvcEvent = $mvcEvent;

        $this->router = $router;
    }

    /**
     * @param $user
     */
    public function exec($user)
    {
        $token = $this->generateToken();

        $this->makeActivationFor($user, $token);

        $this->sendActivationMailTo($user, $token);
    }

    /**
     * @return string
     */
    protected function generateToken()
    {
        $string = "";
        $length = 40;

        while (($len = strlen($string)) < $length) {
            $size = $length - $len;

            $bytes = random_bytes($size);

            $string .= substr(str_replace(['/', '+', '='], '', base64_encode($bytes)), 0, $size);
        }

        return $string;
    }

    /**
     * @param $user
     * @param $token
     */
    protected function makeActivationFor($user, $token)
    {
        $product = new Activation();
        $product->setUserId($user->getId());
        $product->setToken($token);

        $entityManager = $this->mvcEvent->getApplication()->getServiceManager()
            ->get('doctrine.entitymanager.orm_default');

        $entityManager->persist($product);
        $entityManager->flush();
    }

    /**
     * @param $user
     * @param $token
     * @return bool
     */
    protected function sendActivationMailTo($user, $token)
    {
        try
        {
            $userName = $user->getFirstname();
            $link     =
                $this->getActivationBaseUrlWith($token); // "http://127.0.0.1:8000/activation/fXVy0Ru3aLCCjHoMcM46x0E5SfEDRRbgfgs4lMtG";

            $mail = new Mail\Message();

            $mail->setFrom('noreply@adminpanel.org', 'Административная панель');
            $mail->addTo($user->getEmail(), $user->getDisplayName());
            $mail->setSubject('Активация учетной записи');

            $html = "
                <html>
                <body>
                <p>Привет, <strong>{$userName}!</strong></p>
                    <p>Для завершения регистрации нужно активировать email.</p>
                    <p>Для этого перейди по этой <a href=\"{$link}\">ссылке</a>.</p>
                    <p>Спасибо.</p>
                    <p>---</p>
                    <p>Ваша панель администрирования.</p>
                </body>
                </html>
            ";

            $bodyPart = new \Zend\Mime\Message();

            $bodyMessage       = new \Zend\Mime\Part($html);
            $bodyMessage->type = 'text/html';

            $bodyPart->setParts([$bodyMessage]);

            $mail->setBody($bodyPart);
            $mail->setEncoding('UTF-8');

            $transport = new Mail\Transport\Sendmail();
            $transport->send($mail);
        }
        catch (\Exception $e)
        {
            return false;
        }

        return true;
    }

    /**
     * @param $token
     * @return string
     */
    protected function getActivationBaseUrlWith($token)
    {
        $url    = $this->router->assemble($this->mvcEvent->getParams(), array('name' => 'activation'));

        $serverUrl = $this->mvcEvent->getApplication()->getServiceManager()->get('ViewHelperManager')->get('ServerUrl');
        $baseUrl = $serverUrl->__invoke();

        return $baseUrl . $url . $token;
    }
}