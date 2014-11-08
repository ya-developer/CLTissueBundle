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


## Step 5) Configure an adapter for your virus-scanner

As mentioned before, this bundle integrates the [Tissue](https://github.com/cleentfaar/tissue) library. To make it easy
for you to get started on scanning files, I've also decided to include two adapters for you to pick from (`clamav` and `clamavphp`).

To decide on which adapter to use, you have the following options:

#### Option 1) Using the ClamAV adapter (easiest)
The simplest option is to use the clamav adapter, this is the default configuration so there is nothing to configure!
Obviously, it does require you to have the `clamav` and, if you don't do any further configuration, `clamav-daemon`
packages installed. You can read more about these packages and how to install them in [the adapter's own installation documentation](https://github.com/cleentfaar/tissue-clamav-adapter/Resources/doc/installation.md).

### Option 2) Using the ClamAVPHP adapter (little more work)
If you want to use the `clamavphp` adapter, it's simply a matter of configuring the `adapter` option:
```yaml
# app/config.yaml
cl_tissue:
    adapter: clamavphp
```
And that's it! Obviously, you need to make sure you have installed the `clamav` package and `clamav` PHP-extension before
continuing. You can read more about these packages and how to install them in [the adapter's own installation documentation](https://github.com/cleentfaar/tissue-clamavphp-adapter/Resources/doc/installation.md).

### Using your own adapters (advanced)
The [Tissue](https://github.com/cleentfaar/tissue) library that this bundle implements is highly abstracted, this means
that if you have a different virusscanner installed on your server, you can just create your own adapter for it!
To do this, you only need to create a service for your adapter and tag it with `cl_tissue.adapter`, as follows:
```yaml
acme.my_third_party_adapter:
    class: Acme\Security\ThirdPartyAdapter
    tags:
      - { name: cl_tissue.adapter, alias: my_third_party }
```
Note the `alias` attribute, this is used to reference your scanner further on in your code.


# Ready?

In the next chapter I will be showing you how you can scan file uploads in your Symfony project.

Check out the [usage documentation](usage.md)!
