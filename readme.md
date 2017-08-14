### Botble CMS
A platform base on Laravel Framework.

## Documents

On this projects, I use the latest Laravel version (currently 5.3). Please go to laravel documentation page for more information.

## Requirement

- Apache, nginx, or another compatible web server.
- PHP >= 5.6.4 >> Higher
- MySQL Database server
- OpenSSL PHP Extension
- Mbstring PHP Extension
- Exif PHP Extension
- Fileinfo Extension
- Module Re_write server
- PHP_CURL Module Enable

## Installation

## Using install shell script

#### For Linux users:

 If you got this error /usr/bin/env: ‘bash\r’: No such file or directory"
 
 Please run `sed $'s/\r$//' ./install.sh > ./install.Unix.sh` and use ./install.Unix.sh to install

 If you got this error "bash: ./install.sh: Permission denied"
 
 Please run `sudo chmod 777 -R install.sh` to make sure this file has permission to execute

#### Install step
 - Run file ./install.sh

### Manual installation

* Import sample database from `database/dump/base.sql`
  - Default Admin URL `/admin`
  - Default username and password is `botble` - `159357`
* Create `.env` file from `.env-example` and update your configuration
```
APP_DEBUG=true
APP_ENV=local
APP_URL=http://cms.local

APP_KEY=lpivZFWb3A4xtCc6S4f3eSdE3jdP5hWm
APP_LOG=daily
APP_LOG_LEVEL=debug

BROADCAST_DRIVER=log
CACHE_DRIVER=file
SESSION_DRIVER=file
QUEUE_DRIVER=sync

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_STRICT=false

DB_DATABASE=your_database
DB_PASSWORD=your_db_password
DB_USERNAME=your_db_username

// If you want to change admin directory
ADMIN_DIR=admin

// Please follow document in core/dashboard/_docs to get below information
ANALYTICS_VIEW_ID=125311257 (google analytics view id)
ANALYTICS_CERTIFICATE_PATH=files/analytics.json (path to your json credential) 

// Mail information (https://laravel.com/docs/5.3/mail)
MAIL_DRIVER=smtp
MAIL_HOST=mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=
MAIL_PASSWORD=
MAIL_ENCRYPTION=null

// Register an account here https://www.google.com/recaptcha and update it. Document: https://github.com/ARCANEDEV/noCAPTCHA/tree/master/_docs.
NOCAPTCHA_SECRET=your-secret-key
NOCAPTCHA_SITEKEY=your-site-key

// You google plus account. For SEO only.
SEO_MISC_AUTHOR=https://plus.google.com/u/0/108777531278319302531

// Use for get youtube video information. https://developers.google.com/youtube/v3/getting-started
YOUTUBE_DATA_API_KEY=AIzaSyCV4fmfdgsValGNR3sc-0W3cbpEZ8uOd60

// Use for create backup source and db to Amazone S3
AWS_KEY=
AWS_SECRET=
AWS_REGION=
AWS_BUCKET=
AWS_PATH=

// Use for Facebook login
FACEBOOK_APP_ID=
FACEBOOK_APP_SECRET=
FACEBOOK_APP_REDIRECT=

// Use for Google login
GOOGLE_APP_ID=
GOOGLE_APP_SECRET=
GOOGLE_APP_REDIRECT=

// Use for Github login
GITHUB_APP_ID=
GITHUB_APP_SECRET=
GITHUB_APP_REDIRECT=

```
* Optional (this zip file include vendor libraries so you don't need to do it):
  - Open CMD and run `composer install` or `composer update` to install vendor packages.
* Run your app on browser

###Note

This site can only be run at domain name, not folder link.

On your localhost, setting virtual host. Something like `http://cms.local` is ok. 

Cannot use as `http://localhost/cms/...`.

Please remove `public` in your domain also, you can point your domain to `public` folder

or use `.httaccess` (http://stackoverflow.com/questions/23837933/how-can-i-remove-public-index-php-in-the-url-generated-laravel)

Follow these steps to see how to config virtual host: `/docs/2. Setup vitual host`.

Well done! Now, you can login to the dashboard by access to your_domain_site/admin.

```
Username: botble
Password: 159357
```

Enjoy!