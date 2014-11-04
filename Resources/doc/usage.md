# Usage

## Scanning a file upload

Let's assume you allow your users to upload files to your website. Odds are you want to be sure those files do not contain
any viruses so you want to scan those files before permanently storing them somewhere on your server.

First we need a form that let's us process a file upload. If you created a separate `FormType` for it (good boy!), it  would look something like this:

```php
<?php

namespace Acme\DemoBundle\Form\Type;

use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class DemoUploadType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('my_file_field', 'file', [
            'constraints' => [
                new File()
            ]
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'demo_upload';
    }
}

```

Now, to have this upload scanned as well as be a valid file, you just need to replace the `File` constraint with this bundle's
 `VirusFreeFile`-constraint.
```php
<?php
$builder->add('my_file_field', 'file', [
    'constraints' => [
        //new File()
        new VirusFreeFile()
    ]
]);
```

NOTE: I kept this form simple and added constraints directly, your application might have the constraints loaded from
annotations in a related `data_class`.


### So what does the constraint do?

During validation, the constraint will cause Tissue to first scan the uploaded file before passing it to it's parent's (`File`) constraint.
If a virus is detected, processing is immmediately stopped and an error is raised. The scanner used for this detection
is determined by which service you configured in the previous chapter.


### Example of upload in a controller

Below is an example of what your action could look like if you used the form above to upload files:
```php
<?php

namespace Acme\DemoBundle\Controller;

// use ...

class DemoController extends Controller
{
    public function uploadAction(Request $request)
    {
        // let's say you have created a form with a field of the type 'file' in it
        $form = $this->createForm('...');

        $form->handleRequest($request);
        if ($form->isValid()) {
            // yay! the file contains no viruses (according to your scanner)!

            // @var UploadedFile $uploadedFile
            $uploadedFile = $form->getData()['uploaded_file'];

            // you probably have your own logic for processing uploads, this is just a simple example
            $uploadedFile->move('/path/to/some/pretty/place', 'clean_file.txt');
        }
    }
}
```


## IMPORTANT

Although this should be enough to keep any evil-doers from uploading viruses to this particular form, you must keep in
mind that there are many more ways to abuse uploads than just uploading a virus.

For instance, if you were to keep this file available within your web-directory after it has been uploaded, you better
make sure that there is NO CHANCE that the file may get executed by your application in one way or another.

A malicious user could simply upload a piece of PHP-code (no virus!) that will open your application up to a huge range
of leaks. Again, that's just one of the reasons that you should not solely rely on this package protecting your site!

