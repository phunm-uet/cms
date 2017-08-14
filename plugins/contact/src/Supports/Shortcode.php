<?php

namespace Botble\Contact\Supports;

class Shortcode
{

    /**
     * Filter constructor.
     */
    public function __construct()
    {
        add_shortcode('CONTACT_FORM', [$this, 'form']);
    }

    /**
     * Trigger __construct function
     *
     * @return Shortcode
     */
    public static function initialize()
    {
        return new self();
    }

    /**
     * @return string
     */
    public function form($shortcode)
    {
        return view('contact::forms.contact', ['header' => $shortcode->header])->render();
    }
}