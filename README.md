# Introduction

Keeping track of dependencies for every application you develop is not an easy task. Zend_Debug_Include is a Zend Framework component that helps you track and manage dependencies.

We all agree that dependencies cannot be maintained by hand, it's almost impossible, specially if you are using packages from Zend, Solar, Maintainable and/or Zym. Every time you add an include/require statement or create an instance of an object, you introduce a new dependency. And that's why tracking internal project dependencies can become complex when using a framework.

The concept behind Zend_Debug_Include is that the dependencies for each source file are stored in a separate file. If the source file is modified, the file containing that source file's dependencies is rebuilt. This concept enables you to determine run-time dependencies of files using arbitrary components. This solution is also useful if you are deploying your application using Linux packages. But, dependency tracking isn't just useful for deploying applications, it can also be used to evaluate packages. Sometimes packages create unnecessary dependencies and this is something that we need to monitor.

Zend_Debug_Include comes with 3 built-in adapters: File, Package and Url.

## Tracking File Dependencies

To track file dependencies you need to create an instance of Zend_Debug_Include_Manager and set the Zend_Debug_Include_Adapter_File adapter. This needs to happen inside your bootstrapper file, before the Front Controller dispatches the request.

```php
<?php

$included = new Zend_Debug_Include_Manager();
$included->setAdapter(new Zend_Debug_Include_Adapter_File());
$included->setOutputDir('/var/www/my-app/dependencies');

/* Dispatch request */
$frontController->dispatch();
```

This creates a zf-files.txt in your output directory containing all the files included or required on that request. Every time the Front Controller dispatches a request, Zend_Debug_Include checks for new dependencies and adds them to the zf-files.txt file:

```
/var/www/my-app/public/index.php
/var/www/my-app/application/bootstrap.php
/var/www/my-app/library/Zend/Loader.php
/var/www/my-app/library/Zend/Controller/Front.php
/var/www/my-app/library/Zend/Controller/Action/HelperBroker.php
/var/www/my-app/library/Zend/Controller/Action/HelperBroker/PriorityStack.php
/var/www/my-app/library/Zend/Controller/Exception.php
/var/www/my-app/library/Zend/Exception.php
/var/www/my-app/library/Zend/Controller/Plugin/Broker.php
/var/www/my-app/library/Zend/Controller/Plugin/Abstract.php
/var/www/my-app/library/Zend/Controller/Dispatcher/Standard.php
/var/www/my-app/library/Zend/Controller/Dispatcher/Abstract.php
/var/www/my-app/library/Zend/Controller/Dispatcher/Interface.php
/var/www/my-app/library/Zend/Controller/Request/Abstract.php
/var/www/my-app/library/Zend/Controller/Response/Abstract.php
/var/www/my-app/library/Zend/Controller/Router/Route.php
/var/www/my-app/library/Zend/Controller/Router/Route/Abstract.php
/var/www/my-app/library/Zend/Controller/Router/Route/Interface.php
/var/www/my-app/library/Zend/Config.php
... (63 files in total) ...
```

To change the name of the file:

```php
<?php

$included = new Zend_Debug_Include_Manager();
$included->setOutputDir('/var/www/my-app/dependencies');
$included->setFilename('files.dep');
...
```

## Tracking Package Dependencies

Similar to Zend_Debug_Include_Adapter_File, but groups all the files into packages.

```php
<?php

$included = new Zend_Debug_Include_Manager();
$included->setAdapter(new Zend_Debug_Include_Adapter_Package());
$included->setOutputDir('/var/www/my-app/dependencies');
```

The code above creates a zf-packages.txt file and adds the following data:

```
Zend/Loader.php
Zend/Controller
Zend/Exception.php
Zend/Config.php
Zend/Debug
Zend/View.php
Zend/View
Zend/Loader
Zend/Uri.php
Zend/Filter
Zend/Filter.php
```

Now, if you introduce a new dependency, for example, Zend_Mail:

```php
<?php

class IndexController extends Zend_Controller_Action
{
    public function indexAction()
    {
        $mail = new Zend_Mail();
        $this->view->message  = 'test';
    }
}
```

The next time the Front Controller dispatches the request and calls the index action, Zend_Debug_Include will automatically add the Zend_Mail package and all its dependencies to the zf-packages.txt file:

```
Zend/Loader.php
Zend/Controller
Zend/Exception.php
Zend/Config.php
Zend/Debug
Zend/View.php
Zend/View
Zend/Loader
Zend/Uri.php
Zend/Filter
Zend/Filter.php
Zend/Mail.php
Zend/Mail
Zend/Mime.php
Zend/Mime
```

You can then use this information to keep track of dependencies and tell your build tool the name of the files and directories you need to copy and package.

## External Dependencies

Here is where everything starts to make sense. Zend_Debug_Include allows you to search for external dependencies as well, you just need to tell the Adapter the libraries you are using. For example:

```php
<?php

$libraries = array('Zend', 'Solar');
$adapter = new Zend_Debug_Include_Adapter_Package($libraries);

$included = new Zend_Debug_Include_Manager();
$included->setAdapter($adapter);
$included->setOutputDir('/var/www/my-app/dependencies');
```

The Solar packages will also be added to the zf-packages.txt file:

```
Zend/Loader.php
Zend/Controller
Zend/Exception.php
Zend/Config.php
Zend/Debug
Zend/View.php
Zend/View
Zend/Loader
Zend/Uri.php
Zend/Filter
Zend/Filter.php
Zend/Mail.php
Zend/Mail
Zend/Mime.php
Zend/Mime
Solar/Base.php
Solar/File.php
Solar/Factory.php
Solar/Sql.php
Solar/Sql
Solar/Cache.php
Solar/Cache
```

## URL Adapter

If you want to create a different file for each request, use the URL adapter instead:

```php
<?php

$included = new Zend_Debug_Include_Manager();
$included->setAdapter(new Zend_Debug_Include_Adapter_Url());
$included->setOutputDir('/var/www/my-app/dependencies');
```

The URL adapter maps the URL path to a filename. So, if you request the following URI:

```
http://my-app/blog/2009/02/01
```

It creates the file blog_2009_02_01.txt.

## License

- New BSD License http://www.opensource.org/licenses/bsd-license.php
- Copyright (c) 2010, Federico Cargnelutti. All rights reserved.