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



### Step 2) Get a virus-scanner

This bundle integrates the Tissue library, adding a `VirusFreeFile`-constraint for your validation and an easy to use
service to access your favorite virus scanner whenever you need to (`cl_tissue.scanner`).

But since every environment may have different requirements on what anti-virus software is used, it's licenses, and how
it is configured, this bundle does not force you to use a specific engine.

In fact; the choice is up to you, as long as you are willing to make an adapter for it.

To save you some headache however, the following adapters are already available for you to use:

- [ClamAV](https://github.com/cleenfaar/tissue-clamav-adapter)
- [ClamAV (PHP extension)](https://github.com/cleenfaar/tissue-phpclamav-adapter)

Using one of these adapters is a matter of adding the requirement to your `composer.json`:
```json
    "require": {
        "cleentfaar/tissue-clamav-adapter": "dev-master"
    }
```


## Step 3) Register the namespaces (without composer)

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


## Step 5) Creating a service for your adapter

If you followed all the steps above, you will have included an adapter for the virus-scanner of your choice in your
`composer.json`. But since Tissue cannot know the requirements of every adapter beforehand, you will need to create a
service for your adapter first.

Here's an example of a service that uses the [ClamAV](https://github.com/cleentfaar/tissue-clamav-adapter) adapter:
```yaml
# Acme/DemoBundle/Resources/config/services.yml
cl_tissue.scanner:
    class: CL\Tissue\Adapter\ClamAV\ClamAVAdapter
    arguments:
        - /usr/bin/clamdscan
```

Note the name of this service; you should not change it. I am working on a way of making this more flexible but until
that you will just have to stick with using this specific name.

And that's it!


# Ready?

In the next chapter I will be showing you how you can scan file uploads in your Symfony project.

Check out the [usage documentation](usage.md)!
