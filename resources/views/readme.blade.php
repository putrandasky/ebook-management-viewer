<x-markdown>
## About The App and Features
This Web App is a Platform for Ebook Management with interactive feature such as :

- Bookmark a page (with note)
- Crop the page (downloadable and share link feature)
- Print on page (landscape or portrait orientation with desired page range)
- Convert to PDF (downloadable and share link feature)
- Copy link current ebook
- Search the title of page
- Can create multiple ebook with desired link
- Mobile friendly

## How to Install

### Server Requirements & Configuration
This app is using on top Laravel Framework 8 with few server requirements as below

- PHP >= 7.3
- BCMath PHP Extension
- Ctype PHP Extension
- Fileinfo PHP Extension
- JSON PHP Extension
- Mbstring PHP Extension
- OpenSSL PHP Extension
- PDO PHP Extension
- Tokenizer PHP Extension
- XML PHP Extension
- MYSQL
- Imagick / GD Extension
- Composer

> Please make sure you know how to configure a server with nginx

For server configuration please refer to Laravel documentation [here](https://laravel.com/docs/8.x/deployment#nginx)

Please ensure the root of the application to public folder for nginx server configuration
```php

// inside file /etc/nginx/sites-available/example.com.conf
root /var/www/example.com/public;
```
don't forget create link to sites enable in terminal

```
ln -s /etc/nginx/sites-available/example.com.conf /etc/nginx/sites-enabled/
```

also ensure the current php-fpm version of the server is same with the server configuration
```php
// inside file /etc/nginx/sites-available/example.com.conf
fastcgi_pass unix:/var/run/php/php7.4-fpm.sock;
```

and ensure add code as below in configuration file
```php
// inside file /etc/nginx/sites-available/example.com.conf
location / {
try_files $uri $uri/ /index.php?$query_string;
}
```

> **Please make sure the app running in HTTPS protocol or it will won't work properly**

### Authorization Storage
Since laravel framework need authorization to store the file in storage folder
please change the owner and authorization with command as below in root folder of application

```
chown -R $USER:www-data storage
chmod -R 775 storage
```
and run command below so the storage folder can be accessed to public asset folder

```
php artisan storage:link
```

### Initation of the App

After make sure all the server requirement and application folder is stored in the server. run composer to download dependencies of the application as below inside root of folder application
```
composer install
```
```
npm install
```
please make sure the server already installed [Composer](https://getcomposer.org/download/)

and also Install [NodeJs](https://www.freecodecamp.org/news/how-to-install-node-js-on-ubuntu-and-update-npm-to-the-latest-version/)
```
APP_NAME=yourwebname
APP_ENV=production
APP_KEY=base64:zAYYuQxxgyeZi6dULoWcOmLv9GeDaDcNZc2eqWR2EAM=
APP_DEBUG=false
APP_URL=https://subdomain.domain.com
SANCTUM_STATEFUL_DOMAINS=*.domain.com
SPA_URL=https://subdomain.domain.com
SESSION_DOMAIN=.domain.com

DB_DATABASE=yourdatabasename
DB_USERNAME=yourdatabaseusername
DB_PASSWORD=yourdatabasepassword
SESSION_DRIVER=cookie
```

Then, please run command below in root of folder
```
composer dump-autoload
```
nb : command above should run after .env file changed

### Databases

After we change DB data in .env file. please create database in MySQL with database name same as with DB_DATABASE in .env file
if the database created, we can run command as below to create requirements table for the application
```
php artisan migrate
```

### FIrst Admin User

For first admin user so we can login in admin panel to manage Ebook. please run command below
```
php artisan db:seed --class=UserSeeder
```

so we can login in https://subdomain.domain.com/admin/auth/login with trial account below
email : trial@mail.com
psss : trial123

IMPORTANT : Please add new user in after login with trial account https://subdomain.domain.com/admin/auth/users and delete the trial account after it.

## How to Use

### Admin Area
Before we can read an ebook in this app, the first thing to do is we have to upload ebook pages in Admin Area
> Admin area link : https://subdomain.domain.com/admin/auth/login

> File Type : Jpeg file only
> File Size : < 1mb
### Creating new Ebook
For creating new ebook, please click above right button "Add Ebook" then input ebook name and slug name> slug name must be unique from another ebook, since it will display as a link in ebook reader mode
> ex : https://subdomain.domain.com/reader/this-is-your-ebook-slug

### Add Chapters & Pages

If we enter the ebook by click "enter" button on the ebook we created. We can add Chapters & Pages by click "Add Chapter" on above right button.
>Eventhough the button name is "Add Chapter" we can add the page as well
>Chapter mean like a folder to categorized the section of the pages into subsection

Chapter page can be rename it into alias such as : Cover, I, II, A, B ,etc

> If we rename the chapter folder or page outside folder, we have to rename it manually for all chapter folder and pages outside folder to get proper ordering number of the pages and chapter.


### Add Pages Inside Chapter

We can go deeper inside chapter and add pages in it by click the "magnify glass" button in folder icon after we create a chapter.

> Pages can not be renamed inside chapter. Page name inside chapter only ordering of the pages.

For adding a page, please click "Add Pages" button on above right button then we can drag and drop or bulk upload the jpeg file as a desired page. This operation applied for adding a pages in chapter mode as well.

### Feature Inside a Pages

The pages we created with jpeg file have a feature as below

- Title pages renamed
- Mark the page to show in Content Navigation in reader mode
- Create Hotspot area and assign a link in it so the page in reader mode has a function to open the link when we click on hotspot area in reader mode

### Reader Mode

After we setup an ebook and add a pages, we can access the reader mode with link as below
https://subdomain.domain.com/reader/your-slug-book-name


### Application Logo
To change application logo, please replace file logo.png in folder below
```
/public/images/logo.png
```

## Things to be noted

Please free up regularly a storage in a folder where pdf conversion stored
```
/storage/app/public/pdf
```
also a folder where shared file after cropping page stored
```
/storage/app/public/shared
```
</x-markdown>
