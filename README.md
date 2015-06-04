Silex 2.x Error Provider
==========================

Catching all server side errors, then log them in the error log file.
Then console command moves the content of the file into db.


## Installing the ErrorProvider in a Silex project
The installation process is actually very simple.  Set up a Silex project with Composer.

Once the new project is set up, open the composer.json file and add the exs/silex-error-provider as a dependency:
``` js
//composer.json
//...
"require": {
        //other bundles
        "exs/silex-error-provider": "v1.0.*"
```
Or you could just add it via the command line:
```
$ composer.phar require exs/silex-error-provider : v1.0.*
```

Save the file and have composer update the project via the command line:
``` shell
php composer.phar update
```
Composer will now update all dependencies and you should see our bundle in the list:
``` shell
  - Installing exs/silex-error-provider (dev-master 463eb20)
    Cloning 463eb2081e7205e7556f6f65224c6ba9631e070a
```

Update the app.php to include our provider:
``` php
//app.php
//...
$app->register(new \EXS\ErrorProvider\Providers\Services\ErrorServiceProvider());
```
Update your config.php with the log locations:
```php
//...
// Log locations and names
// Max number of error messages to read before logging to DB.
$app['logs.directory'] = __DIR__ . '/../var/logs';
$app['logs.file.exceptions'] = $app['logs.directory'] . '/exceptions.log';
$app['logs.reader.threshold'] = 2000;
//...
```
Now the application is logging Exceptions in /exceptions.log


## Console Command Usage

Update your database schema by run console doctrine command 
or 
```sql
CREATE TABLE exception4xx (id INT AUTO_INCREMENT NOT NULL, statusCode INT NOT NULL, message LONGTEXT DEFAULT NULL, requestUrl VARCHAR(255) DEFAULT NULL, referrer VARCHAR(255) DEFAULT NULL, userAgent VARCHAR(255) DEFAULT NULL, remoteIp VARCHAR(45) DEFAULT NULL, method VARCHAR(10) DEFAULT NULL, queryString LONGTEXT DEFAULT NULL, hostname VARCHAR(255) DEFAULT NULL, request LONGTEXT DEFAULT NULL, logged DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
CREATE TABLE exception5xx (id INT AUTO_INCREMENT NOT NULL, statusCode INT DEFAULT NULL, file VARCHAR(255) DEFAULT NULL, line INT DEFAULT NULL, message LONGTEXT DEFAULT NULL, trace LONGTEXT DEFAULT NULL, requestUrl VARCHAR(255) DEFAULT NULL, referrer VARCHAR(255) DEFAULT NULL, userAgent VARCHAR(255) DEFAULT NULL, remoteIp VARCHAR(45) DEFAULT NULL, method VARCHAR(10) DEFAULT NULL, queryString LONGTEXT DEFAULT NULL, hostname VARCHAR(255) DEFAULT NULL, request LONGTEXT DEFAULT NULL, logged DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
```


Add the command to your console.php file
```php
use Symfony\Component\Console\Application;
use EXS\ErrorProvider\Commands\LogloaderCommand;

$console = new Application('Log loader command', 'Description here');
$console->addCommands(array(
    new LogloaderCommand('Exception reader', $app['exs.serv.exception.reader'])
));

return $console;
```
Go to your shell window, then execute
```shell 
php bin/console exs:log:exceptions
```
And now the file will be saved on your Database.

If you wish to save exceptions to DB regularly, add the console command to your crontab.



#### Contributing ####
Anyone and everyone is welcome to contribute.

If you have any questions or suggestions please [let us know][1].

[1]: http://www.ex-situ.com/