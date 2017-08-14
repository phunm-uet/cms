<?php

namespace Botble\Base\Supports;

use Botble\Base\Events\SendMailEvent;
use Exception;
use Symfony\Component\Debug\Exception\FlattenException;
use Symfony\Component\Debug\ExceptionHandler as SymfonyExceptionHandler;

class EmailHandler
{

    /**
     * @param $view
     */
    public function setEmailTemplate($view)
    {
        config()->set('cms.email_template', $view);
    }

    /**
     * @param $content
     * @param $title
     * @param $args
     * @author Sang Nguyen
     */
    public function send($content, $title, $args)
    {
        try {
            event(new SendMailEvent($content, $title . ' - ' . setting('site_title'), $args));
        } catch (Exception $ex) {
            info($ex->getMessage());
        }
    }

    /**
     * Sends an email to the developer about the exception.
     *
     * @param  \Exception  $exception
     * @return void
     * @author Sang Nguyen
     */
    public function sendErrorException(Exception $exception)
    {
        if (app()->environment() != 'local') {
            try {
                $ex = FlattenException::create($exception);

                $handler = new SymfonyExceptionHandler();

                $content = $handler->getContent($ex);

                EmailHandler::send($content, $exception->getFile(), ['to' => setting('admin_email'), 'name' => setting('site_title')]);
            } catch (Exception $ex) {
                info($ex->getMessage());
            }
        }
    }
}