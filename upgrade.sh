#!/usr/bin/env bash


read -p "Please enter your new source folder (new source code that you downloaded from Codecanyon): " source

cd "$source"

rm -rf "./resources/views/errors" && cp -R "$source/resources/views/errors" "./resources/views"
rm -rf "./resources/lang/vendor/acl" && cp -R "$source/resources/lang/vendor/acl" "./resources/lang/vendor"
rm -rf "./resources/lang/vendor/analytics" && cp -R "$source/resources/lang/vendor/analytics" "./resources/lang/vendor"
rm -rf "./resources/lang/vendor/audit-logs" && cp -R "$source/resources/lang/vendor/audit-logs" "./resources/lang/vendor"
rm -rf "./resources/lang/vendor/backup" && cp -R "$source/resources/lang/vendor/backup" "./resources/lang/vendor"
rm -rf "./resources/lang/vendor/bases" && cp -R "$source/resources/lang/vendor/bases" "./resources/lang/vendor"
rm -rf "./resources/lang/vendor/block" && cp -R "$source/resources/lang/vendor/block" "./resources/lang/vendor"
rm -rf "./resources/lang/vendor/blog" && cp -R "$source/resources/lang/vendor/blog" "./resources/lang/vendor"
rm -rf "./resources/lang/vendor/captcha" && cp -R "$source/resources/lang/vendor/captcha" "./resources/lang/vendor"
rm -rf "./resources/lang/vendor/contact" && cp -R "$source/resources/lang/vendor/contact" "./resources/lang/vendor"
rm -rf "./resources/lang/vendor/custom-field" && cp -R "$source/resources/lang/vendor/custom-field" "./resources/lang/vendor"
rm -rf "./resources/lang/vendor/dashboard" && cp -R "$source/resources/lang/vendor/dashboard" "./resources/lang/vendor"
rm -rf "./resources/lang/vendor/gallery" && cp -R "$source/resources/lang/vendor/gallery" "./resources/lang/vendor"
rm -rf "./resources/lang/vendor/language" && cp -R "$source/resources/lang/vendor/language" "./resources/lang/vendor"
rm -rf "./resources/lang/vendor/log-viewer" && cp -R "$source/resources/lang/vendor/log-viewer" "./resources/lang/vendor"
rm -rf "./resources/lang/vendor/media" && cp -R "$source/resources/lang/vendor/media" "./resources/lang/vendor"
rm -rf "./resources/lang/vendor/menu" && cp -R "$source/resources/lang/vendor/menu" "./resources/lang/vendor"
rm -rf "./resources/lang/vendor/menu-left-hand" && cp -R "$source/resources/lang/vendor/menu-left-hand" "./resources/lang/vendor"
rm -rf "./resources/lang/vendor/pages" && cp -R "$source/resources/lang/vendor/pages" "./resources/lang/vendor"
rm -rf "./resources/lang/vendor/request-log" && cp -R "$source/resources/lang/vendor/request-log" "./resources/lang/vendor"
rm -rf "./resources/lang/vendor/settings" && cp -R "$source/resources/lang/vendor/settings" "./resources/lang/vendor"
rm -rf "./resources/lang/vendor/translations" && cp -R "$source/resources/lang/vendor/translations" "./resources/lang/vendor"
rm -rf "./resources/lang/vendor/widgets" && cp -R "$source/resources/lang/vendor/widgets" "./resources/lang/vendor"

cp -R "$source/install.sh" ./
cp -R "$source/package.json" ./

echo -e "\033[32mUpgrade core...\033[0m"
rm -rf ./core && cp -R "$source/core" ./

echo -e "\033[32mUpgrade core done!\033[0m"

echo -e "\033[32mUpgrade ./plugins...\033[0m"

rm -rf ./plugins/analytics && cp -R "$source/plugins/analytics" ./plugins
rm -rf ./plugins/audit-log && cp -R "$source/plugins/audit-log" ./plugins
rm -rf ./plugins/backup && cp -R "$source/plugins/backup" ./plugins
rm -rf ./plugins/block && cp -R "$source/plugins/block" ./plugins
rm -rf ./plugins/captcha && cp -R "$source/plugins/captcha" ./plugins
rm -rf ./plugins/contact && cp -R "$source/plugins/contact" ./plugins
rm -rf ./plugins/custom-field && cp -R "$source/plugins/custom-field" ./plugins
rm -rf ./plugins/language && cp -R "$source/plugins/language" ./plugins
rm -rf ./plugins/log-viewer && cp -R "$source/plugins/log-viewer" ./plugins
rm -rf ./plugins/note && cp -R "$source/plugins/note" ./plugins
rm -rf ./plugins/request-log && cp -R "$source/plugins/request-log" ./plugins
rm -rf ./plugins/translation && cp -R "$source/plugins/translation" ./plugins
rm -rf ./plugins/gallery && cp -R "$source/plugins/gallery" ./plugins

echo -e "\033[32mUpgrade ./plugins done!\033[0m"

echo -e "\033[32mUpgrade ./public/vendor ...\033[0m"

rm -rf "./public/vendor/core/css" && cp -R "$source/public/vendor/core/css" "./public/vendor/core"
rm -rf "./public/vendor/core/js" && cp -R "$source/public/vendor/core/js" "./public/vendor/core"
rm -rf "./public/vendor/core/images" && cp -R "$source/public/vendor/core/images" "./public/vendor/core"
rm -rf "./public/vendor/core/fonts" && cp -R "$source/public/vendor/core/fonts" "./public/vendor/core"
rm -rf "./public/vendor/core/packages" && cp -R "$source/public/vendor/core/packages" "./public/vendor/core"

rm -rf "./public/vendor/core/plugins/backup" && cp -R "$source/public/vendor/core/plugins/backup" "./public/vendor/core/plugins"
rm -rf "./public/vendor/core/plugins/custom-field" && cp -R "$source/public/vendor/core/plugins/custom-field" "./public/vendor/core/plugins"
rm -rf "./public/vendor/core/plugins/language" && cp -R "$source/public/vendor/core/plugins/language" "./public/vendor/core/plugins"
rm -rf "./public/vendor/core/plugins/translation" && cp -R "$source/public/vendor/core/plugins/translation" "./public/vendor/core/plugins"

echo -e "\033[32mUpgrade ./public/vendor done!\033[0m"

read -p "Did you updated composer.json and gulpfile.js? " yn
case $yn in
    [Yy]* )
        php composer.phar update
        php artisan migrate
        php artisan ide-helper:generate
        php artisan cache:clear
        php artisan log:clear
        php artisan view:clear
        php artisan route:list
        npm update
        gulp
    ;;
esac

echo -e "\033[31mUpgrade done. composer.json and gulpfile.js are not automatic upgrade, you need to compare your version with new source code to update.\033[0m"