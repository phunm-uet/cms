<?php

namespace Botble\Base\Supports;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmailAbstract extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var string
     */
    protected $content;

    /**
     * @var string
     */
    protected $title;

    /**
     * Create a new message instance.
     *
     * @param $content
     * @param $title
     */
    public function __construct($content, $title)
    {
        $this->content = $content;
        $this->title = $title;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from(setting('admin_email'))
            ->subject($this->title)
            ->view(config('cms.email_template'))
            ->with(['content' => $this->content]);
    }
}
