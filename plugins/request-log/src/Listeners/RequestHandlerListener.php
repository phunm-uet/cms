<?php

namespace Botble\RequestLog\Listeners;

use Botble\RequestLog\Events\RequestHandlerEvent;
use Botble\RequestLog\Models\RequestLog;
use Request;
use Sentinel;

class RequestHandlerListener
{
    /**
     * @var mixed
     */
    public $requestLog;

    /**
     * RequestHandlerListener constructor.
     * @param RequestLog $requestLog
     * @author Sang Nguyen
     */
    public function __construct(RequestLog $requestLog)
    {
        $this->requestLog = $requestLog;
    }

    /**
     * Handle the event.
     *
     * @param  RequestHandlerEvent $event
     * @return void
     * @author Sang Nguyen
     */
    public function handle(RequestHandlerEvent $event)
    {
        $this->requestLog = RequestLog::firstOrNew([
            'url' => substr(Request::fullUrl(), 0, 255),
            'status_code' => $event->code,
        ]);

        if ($referer = Request::header('referer')) {
            $referers = (array)$this->requestLog->referer ?: [];
            $referers[] = $referer;
            $this->requestLog->referer = $referers;
        }

        if (Sentinel::check()) {
            if (!is_array($this->requestLog->user_id)) {
                $this->requestLog->user_id = [Sentinel::getUser()->id];
            } else {
                $this->requestLog->user_id = array_unique(array_merge($this->requestLog->user_id, [Sentinel::getUser()->id]));
            }
        }

        if (!$this->requestLog->exists) {
            $this->requestLog->count = 1;
        } else {
            $this->requestLog->count += 1;
        }

        $this->requestLog->save();
    }
}
