# Installation

## Step 1) Get the bundle

First you need to get a hold of this bundle. There are two ways to do this:

### Method a) Using composer

Add the following to your ``composer.json`` (see http://getcomposer.org/)

```json
    "require" :  {
        "cleentfaar/tissue-bundle": "~0.1",
    }
```

### Method b) Using submodules

Run the following commands to bring in the needed libraries as submodules.

```bash
git submodule add https://github.com/cleentfaar/TissueBundle.git vendor/bundles/CL/TissueBundle
```



## Step 2) Register the namespaces (without composer)

If you installed the bundle by composer, use the created autoload.php  (jump to step 3).
Add the following two namespace entries to the `registerNamespaces` call in your autoloader:

``` php
<?php
// app/autoload.php
$loader->registerNamespaces(array(
    // ...
    'CL\TissueBundle' => __DIR__.'/../vendor/cleentfaar/tissue-bundle',
    // ...
));
```


## Step 4) Register the bundle

To start using the bundle, register it in your Kernel:

``` php
<?php
// app/AppKernel.php

public function registerBundles()
{
    // ...
    $bundles = array(
        // ...
        new CL\TissueBundle\CLTissueBundle(),
        // ...
    );
    // ...
}
```

## Step 5) Start using the bundle

In the next chapter I will be showing you how you can scan file uploads in your Symfony project.

Check out the [usage documentation](usage.md)!
