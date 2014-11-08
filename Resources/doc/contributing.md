## Contributing


### Coding standards

Your code should follow [Symfony's coding standards](http://symfony.com/doc/current/contributing/code/standards.html) as close as possible.


### Conventions

Your code should follow [Symfony's code conventions](http://symfony.com/doc/current/contributing/code/conventions.html) as close as possible.


### Testing your code locally

Please make sure the unit tests run without failures before - and after - contributing. To do this, simply run your tests
using the phpunit version bundled with the library, like this:

    $ ./bin/phpunit


### Sharing your adapters

You want to create a new adapter for use by the TissueBundle? That's great! I've created a dedicated [how-to](https://github.com/cleentfaar/tissue/Resources/doc/how-to/creating-your-own-adapter.md)
for you to start off with. To let your users include it in their application, you only have to make sure to include the
bundle as a requirement in your package's `composer.json`:
```json
"require": {
    "cleentfaar/tissue-bundle": "~0.1"
}
```

#### Having your adapter included by default

If you are interested in making your adapters available by default in this bundle, I'm happy to include them
(this bundle implements `clamav` already so users don't have to set it up themselves).

Note that, to make them available by default, I must ask you to take care of the following:
    - Your code follows the coding standards mentioned above
    - Your code follows the code conventions mentioned above
    - You've documented what your adapter's requirements are (packages, extensions, configurations...)
    - You've written a test for your adapter that extends the `AdapterTestCase` class (and it passes)

If you still you think your adapter matches these requirements then just create an issue/PR for it on this bundle's GitHub.
I will do my best to get it reviewed/merged as soon as possible.
