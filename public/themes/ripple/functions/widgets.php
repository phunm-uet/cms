<?php

register_sidebar([
    'id' => 'top_sidebar',
    'name' => 'Top sidebar',
    'description' => 'This is top sidebar section',
]);
register_sidebar([
    'id' => 'footer_sidebar',
    'name' => 'Footer sidebar',
    'description' => 'This is footer sidebar section',
]);

require_once __DIR__ . '/../widgets/tags/tags.php';
require_once __DIR__ . '/../widgets/custom-menu/custom-menu.php';
require_once __DIR__ . '/../widgets/recent-posts/recent-posts.php';

register_widget(TagsWidget::class);
register_widget(CustomMenuWidget::class);
register_widget(RecentPostsWidget::class);