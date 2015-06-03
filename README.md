Silex 2.x Error Provider
==========================


## Installing the ErrorProvider in a Silex project
The installation process is actually very simple.  Set up a Silex project with Composer.

Once the new project is set up, open the composer.json file and add the exs/silex-error-provider as a dependency:
``` js
//composer.json
//...
"require": {
        //other bundles
        "exs/silex-error-provider": "dev-master"
```
Or you could just add it via the command line:
```
$ composer.phar require exs/silex-error-provider : dev-master
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


and now you're done.


#### Contributing ####
Anyone and everyone is welcome to contribute.

If you have any questions or suggestions please [let us know][1].

[1]: http://www.ex-situ.com/