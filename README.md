# faucet
bitcoin faucet using fat free framework and faucetbox api


the different ways to install lost faucet

faucet structure

/(some root folder)
    /app            the main classes that run the app (mvc - model - view - controller)
    /captcha_lib    the faucetbox api w/captcha apis
    /lib            the main fat-free-framework that runs the front-controller
    /webroot        entry point for the website (best setup - put these files in web server document root - usually /htdocs or /public_html or /www)


the lost faucet is initially developed to run with web server document root pointed at /webroot folder with the other folders not in public access(best security)

not all providers allow users to change document root folder  and/or put files behind the document root

setup 1)
    most secure
    copy /webroot files and put in server document root
    put other folders behind document root
    edit the /app/config.ini for your setup (see below for settings)


setup 2)
    run the faucet in a subfolder (for users that have established site) e.x yoursite.com/faucet
    copy all folders to subfolder on your site - make note of the folder - you will need it for setting up path 
    edit the /app/config.ini for your setup (see below for settings)
    edit .htaccess
        edit RewriteBase /faucet (change to the folder you installed in)

setup 3)
    run the faucet in a subfolder as the main site (fairly easy to get up and running)
        for users that want to run the faucet as main site but can't use setup 1
    copy all folders to subfolder on your site - make note of the folder - you will need it for settings up path
    edit the /app/config.ini for your setup (see below for settings)
    copy 
        /faucet/.htaccess
        /faucet/index.php to server document root (public_html)

    edit /.htaccess
        edit RewriteBase / (just leave as one slash)
        edit RewriteRule (js.*|css.*|img.*)$ faucet/webroot/$1 [L]  (change faucet to folder you copied files to - only if you copied into different folder)
        
    edit /index.php
        edit  - require '/faucet/webroot/index.php';    (change faucet to folder you copied files to - only if you copied into different folder)


the /app/config.ini settings

[globals]
DEBUG=0 -set this higher for debugging (don't debug live if you can help it)
ROOT=/some/path/to/folder    - set this to the ABSOLUTE path to folder where index.php is located e.x /home/userfolder/public_html (/subfolder if running in a subfolder)
AUTOLOAD="{{ @ROOT }}/app/c/;{{ @ROOT }}/captcha_lib/;"
UI="{{ @ROOT }}/app/v/"
CACHE=true
SITE_NAME=lost-faucet   - you can change this if you want
DB_PREFIX=lost_faucet   - you can change this is you want - especially if you want to run more than one faucet on same database
DB_HOST=localhost       - your database host  - usuallt localhost but some providers use different
DB_NAME=lost            - database name - some providers(shared hosting) create database names with someprefix_dbname - you may have to manually create database to set this up
DSN="mysql:host={{ @DB_HOST }};port=3306;dbname={{ @DB_NAME }}"
DB_USER=    - your database user name  - some providers use dbname_dbuser
DB_PASS=    - your database password




now go to site homepage / folder - installer will ask for database password to verify 

some providers do not allow database user 'create database' privilege - if installer fails you will have to log into your cpanel and create database manually
If you find a use for it and like it - you can help with the code on github - or send a donation so I can keep going - 1iEyvYvT1RkbZf7fMDtFzptcqBBqcgnWx