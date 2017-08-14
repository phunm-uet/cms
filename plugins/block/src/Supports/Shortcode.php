<?php

namespace Botble\Block\Supports;

use Botble\Block\Repositories\Interfaces\BlockInterface;

class Shortcode
{

    /**
     * Shortcode constructor.
     * @author Sang Nguyen
     */
    public function __construct()
    {
        add_shortcode('static-block', [$this, 'render']);
    }

    /**
     * Trigger __construct function
     *
     * @return Shortcode
     * @author Sang Nguyen
     */
    public static function initialize()
    {
        return new self();
    }

    /**
     * @param $shortcode
     * @return null
     * @author Sang Nguyen
     */
    public function render($shortcode)
    {
        $block = app(BlockInterface::class)->getFirstBy(['alias' => $shortcode->alias, 'status' => 1]);

        if (empty($block)) {
            return null;
        }

        return $block->content;
    }
}