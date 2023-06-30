### install is still not ready, database dump is missing

Step 1: clone project
```
 git clone git@github.com:Treibaer/kde-web.git
```
Step 2: set apache Vhost-Config
```
<VirtualHost *:443>
    ServerName example.com
    DocumentRoot "/data/projects/kde-web/www"
    ErrorLog /data/projects/kde-web/logs/error.log
    CustomLog /data/projects/kde-web/logs/access.log common
    SSLEngine on
    SSLCertificateFile .../fullchain.pem
    SSLCertificateKeyFile .../privkey.pem
</VirtualHost>

<Directory "data/projects/kde-web/www">
    AllowOverride All
    Require all granted
</Directory>
```

Step 3. switch to repo-folder
```
cd kde-web
```

Step 4. install dependencies
```
php composer.phar install
```

Step 5. configure conf/config.json

Step 6. restart apache

Step 7. import database (wip)
