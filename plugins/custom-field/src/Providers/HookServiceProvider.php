<?php

namespace Botble\CustomField\Providers;

use Botble\ACL\Repositories\Interfaces\RoleInterface;
use Botble\ACL\Repositories\Interfaces\UserInterface;
use Botble\Blog\Repositories\Interfaces\CategoryInterface;
use Botble\Page\Repositories\Interfaces\PageInterface;
use Illuminate\Support\ServiceProvider;
use CustomField;

class HookServiceProvider extends ServiceProvider
{

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {

    }

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerUsersFields();
        $this->registerPagesFields();
        $this->registerBlogFields();
    }

    /**
     * Register user field rules
     */
    protected function registerUsersFields()
    {
        CustomField::registerRule('Other', 'Logged in user', 'logged_in_user', function () {
            $userRepository = app(UserInterface::class);

            $users = $userRepository->all();

            $userArr = [];
            foreach ($users as $user) {
                $userArr[$user->id] = $user->username . ' - ' . $user->email;
            }

            return $userArr;
        })
        ->registerRule('Other', 'Logged in user has role', 'logged_in_user_has_role', function () {
            $repository = app(RoleInterface::class);

            $roles = $repository->all();

            $rolesArr = [];
            foreach ($roles as $role) {
                $rolesArr[$role->id] = $role->name . ' - (' . $role->slug . ')';
            }

            return $rolesArr;
        });
    }

    /**
     * Register page field rules
     */
    protected function registerPagesFields()
    {
        CustomField::registerRule('Basic', 'Page template', 'page_template', get_page_templates())
            ->registerRule('Basic', 'Page', 'page', function () {
                return app(PageInterface::class)->pluck('name', 'id');
            })
            ->registerRule('Other', 'Model name', 'model_name', [
                'page' => 'Page'
            ]);
    }

    /**
     * Register blog field rules
     */
    protected function registerBlogFields()
    {
        CustomField::registerRuleGroup('Blog')
            ->registerRule('Blog', 'Category', 'category', function () {
                return app(CategoryInterface::class)->pluck('name', 'id');
            })
            ->registerRule('Blog', 'Posts with related category', 'blog.post_with_related_category', function () {
                return app(CategoryInterface::class)->pluck('name', 'id');
            })
            ->registerRule('Other', 'Model name', 'model_name', [
                'post' => '(Blog) Post',
                'category' => '(Blog) Category',
            ]);
    }
}
