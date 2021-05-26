<h1>About The App and Features</h1>
This Web App is a Platform for Ebook Management with interactive feature such as :<br>

<ul>
  <li>Bookmark a page (with note)</li>
  <li>Crop the page (downloadable and share link feature)</li>
  <li>Print on page (landscape or portrait orientation with desired page range)</li>
  <li>Convert to PDF (downloadable and share link feature)</li>
  <li>Copy link current ebook</li>
  <li>Search the title of page</li>
  <li>Can create multiple ebook with desired link</li>
  <li>Mobile friendly</li>
</ul>

<h1>
  How to Install
</h1>

<h2>Server Requirements & Configuration</h2>
This app is using on top Laravel Framework 8 with few server requirements as below <br>
<ul>

  <li> >= 7.3</li>
  <li>BCMath PHP Extension</li>
  <li>Ctype PHP Extension</li>
  <li>Fileinfo PHP Extension</li>
  <li>JSON PHP Extension</li>
  <li>Mbstring PHP Extension</li>
  <li>OpenSSL PHP Extension</li>
  <li>PDO PHP Extension</li>
  <li>Tokenizer PHP Extension</li>
  <li>XML PHP Extension</li>
  <li>MYSQL</li>
  <li>Imagick / GD Extension</li>
  <li>Composer</li>
</ul>

<blockquote style="border-left: 2px solid grey; padding-left:2.5rem">
  Please make sure you know how to configure a server with nginx
</blockquote>

For server configuration please refer to Laravel documentation <a href="https://laravel.com/docs/8.x/deployment#nginx">here</a> <br>

Please ensure the root of the application to public folder for nginx server configuration <br>
<code>

  // inside file /etc/nginx/sites-available/example.com.conf <br>
  root /var/www/example.com/public <br>
</code>
don't forget create link to sites enable in terminal
<br>

<code>
  ln -s /etc/nginx/sites-available/example.com.conf /etc/nginx/sites-enabled/ <br>
</code>

also ensure the current php-fpm version of the server is same with the server configuration <br>
<code>
  // inside file /etc/nginx/sites-available/example.com.conf <br>
  fastcgi_pass unix:/var/run/php/php7.4-fpm.sock; <br>
</code>

and ensure add code as below in configuration file <br>
<code>
  // inside file /etc/nginx/sites-available/example.com.conf <br>
  location / { <br>
  try_files $uri $uri/ /index.php?$query_string; <br>
  } <br>
</code>

> **Please make sure the app running in HTTPS protocol or it will won't work properly** <br>


<h2>Authorization Storage </h2>
Since laravel framework need authorization to store the file in storage folder <br>
please change the owner and authorization with command as below in root folder of application <br>

<code>
  chown -R $USER:www-data storage <br>
  chmod -R 775 storage <br>
</code>
and run command below so the storage folder can be accessed to public asset folder <br>

<code>
  php artisan storage:link <br>
</code>


<h2>Initation of the App</h2>

After make sure all the server requirement and application folder is stored in the server. run composer to download dependencies of the application as below inside root of folder application <br>
<code>
  composer install <br>
</code>
please make sure the server already installed [Composer](https://getcomposer.org/download/) <br>

After downloading dependencies complete modify .env file (or rename .env.example to .env and modify after it) <br>
<code>
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
</code>

Then, please run command below in root of folder <br>
<code>
  composer dump-autoload <br>
</code>
nb : command above should run after .env file changed <br>


<h2>Databases</h2>

<p>

  After we change DB data in .env file. please create database in MySQL with database name same as with DB_DATABASE in .env file
  if the database created, we can run command as below to create requirements table for the application
</p>
<code>
  php artisan migrate
</code>


<h2>FIrst Admin User</h2>

For first admin user so we can login in admin panel to manage Ebook. please run command below
<code>
  php artisan db:seed --class=UserSeeder
</code>

so we can login in https://subdomain.domain.com/admin/auth/login with trial account below
email : trial@mail.com
psss : trial123

IMPORTANT : Please add new user in after login with trial account https://subdomain.domain.com/admin/auth/users and delete the trial account after it.

## How to Use


<h2>Admin Area</h2>
Before we can read an ebook in this app, the first thing to do is we have to upload ebook pages in Admin Area
> Admin area link : https://subdomain.domain.com/admin/auth/login
> File Type : Jpeg file only
> File Size : < 1mb <h2>Creating new Ebook For creating new ebook, please click above right button "Add Ebook" then input ebook name and slug name> slug name must be unique from another ebook, since it will display as a link in ebook reader mode</h2>
  > ex : https://subdomain.domain.com/reader/this-is-your-ebook-slug


  <h2>Add Chapters & Pages</h2>

  If we enter the ebook by click "enter" button on the ebook we created. We can add Chapters & Pages by click "Add Chapter" on above right button.
  >Eventhough the button name is "Add Chapter" we can add the page as well
  >Chapter mean like a folder to categorized the section of the pages into subsection

  Chapter page can be rename it into alias such as : Cover, I, II, A, B ,etc

  > If we rename the chapter folder or page outside folder, we have to rename it manually for all chapter folder and pages outside folder to get proper ordering number of the pages and chapter.



  <h2>Add Pages Inside Chapter</h2>

  We can go deeper inside chapter and add pages in it by click the "magnify glass" button in folder icon after we create a chapter.

  > Pages can not be renamed inside chapter. Page name inside chapter only ordering of the pages.

  For adding a page, please click "Add Pages" button on above right button then we can drag and drop or bulk upload the jpeg file as a desired page. This operation applied for adding a pages in chapter mode as well.


  <h2>Feature Inside a Pages</h2>

  The pages we created with jpeg file have a feature as below

  - Title pages renamed
  - Mark the page to show in Content Navigation in reader mode
  - Create Hotspot area and assign a link in it so the page in reader mode has a function to open the link when we click on hotspot area in reader mode


  <h2>Reader Mode</h2>

  After we setup an ebook and add a pages, we can access the reader mode with link as below
  https://subdomain.domain.com/reader/your-slug-book-name



  <h2>Application Logo</h2>
  To change application logo, please replace file logo.png in folder below
  <code>
    /public/images/logo.png
  </code>

  ## Things to be noted

  Please free up regularly a storage in a folder where pdf conversion stored
  <code>
    /storage/app/public/pdf
  </code>
  also a folder where shared file after cropping page stored
  <code>
    /storage/app/public/shared
  </code>
