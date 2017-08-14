<?php

namespace Botble\Base\Exceptions;

use EmailHandler;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Theme;

class Handler extends ExceptionHandler
{
    /**
     * Render an exception into an HTTP response.
     * @param \Illuminate\Http\Request $request
     * @param Exception $ex
     * @return \Response|\Symfony\Component\HttpFoundation\Response|array
     * @author Sang Nguyen
     */
    public function render($request, Exception $ex)
    {
        if ($ex instanceof ModelNotFoundException) {
            $ex = new NotFoundHttpException($ex->getMessage(), $ex);
        }

        if ($this->isHttpException($ex)) {

            do_action(BASE_ACTION_SITE_ERROR, $ex->getStatusCode());

            switch ($ex->getStatusCode()) {
                case 401:
                    if ($request->is(config('cms.admin_dir') . '/*')) {
                        if ($request->ajax() || $request->wantsJson()) {
                            return ['error' => true, 'message' => trans('acl::permissions.access_denied_message')];
                        } else {
                            return response()->view('bases::errors.401', [], 401);
                        }
                    }
                    break;
                case 404:
                    if ($request->is(config('cms.admin_dir') . '/*')) {
                        return response()->view('bases::errors.404', [], 404);
                    } else {
                        $theme = Theme::uses(setting('theme'))->layout(setting('layout', 'default'));
                        Theme::breadcrumb()->add(__('Home'), route('public.index'))->add('404');
                        return $theme->scope('errors.404', [], 404)->render();
                    }
                    break;

                case 500:
                case 503:
                    if ($request->is(config('cms.admin_dir') . '/*')) {
                        return response()->view('bases::errors.500', [], 500);
                    } else {
                        $theme = Theme::uses(setting('theme'))->layout(setting('layout', 'default'));
                        return $theme->scope('errors.500', [], 500)->render();
                    }
                    break;
            }
        }
        return parent::render($request, $ex);
    }

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Emails.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        if ($this->shouldReport($exception)) {
            EmailHandler::sendErrorException($exception);
        }

        parent::report($exception);
    }
}
