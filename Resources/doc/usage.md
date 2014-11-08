# Usage

## Scanning a file upload

Let's assume you allow your users to upload files to your website. Odds are you want to be sure those files do not contain
any viruses so you want to scan those files before permanently storing them somewhere on your server.

First we need a form that let's us process a file upload. If you created a separate `FormType` for it (good boy!),
it would look something like this:

```php
<?php

namespace Acme\DemoBundle\Form\Type;

use CL\Bundle\TissueBundle\Validator\Constraints\CleanFile;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class DemoUploadType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('uploaded_file', 'file', [
            'constraints' => [
                new CleanFile()
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

NOTE: I kept this form simple and added constraints directly, your application might have the constraints loaded from
annotations in a related `data_class`, but in it works in a similar way.


### So what does the constraint do?

During validation, the constraint will cause the adapter you have configured to scan the uploaded file before passing it
on to the inherited (`File`) constraint.
If a virus is detected, further validation is stopped and an error is raised accordingly. If you set the option to do so,
the uploaded file is also removed immediately.


### Example of an upload in a controller

Below is an example of what your action could look like if you used the form above to upload files:
```php
<?php

namespace Acme\DemoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DemoController extends Controller
{
    public function uploadAction(Request $request)
    {
        // let's say you have created a form with a field of the type 'file' in it
        $form = $this->createForm('...');

        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            // @var UploadedFile $uploadedFile
            $uploadedFile = $form->getData()['uploaded_file'];
            if ($form->isValid()) {
                // yay! the file contains no viruses (according to your scanner)!

                // move the upload to some permanent storage?
                $newFilename = uniqid();
                $uploadedFile->move('/path/to/permanent/storage', $newFilename);

                // ...perhaps store this new path somewhere in your database?
            } else {
                // hm something funny went on...?
                // you'd be wise to remove this file now...
                // NOTE: this is done automatically if you have set the option `autoRemove`
                // to `true` in the field's `CleanFile` constraint
                unlink($uploadedFile->getRealpath());
            }
        }

        // ...
    }
}
```


## IMPORTANT

**I highly recommend you to research the security issues involved before using any of these packages on a production server!**

Although following these steps should be enough to keep most evil-doers from uploading infected files to your form,
I can never give any 100% guarantee! You should take care in keeping your virus-scanner's signature database up-to-date,
otherwise new viruses may get through. But you should also keep in mind that there are many more ways to abuse
uploads than just uploading an infected file!

**Make sure your application cannot be manipulated to execute any of the uploaded files! Not even those deemed 'clean'!**

For instance, if you were to keep this file available within your web-directory after it has been uploaded, you better
make sure that there is NO CHANCE that the file may get executed by your application in one way or another.

A malicious user could simply upload a piece of PHP-code (no virus!) that will open your application up to a huge range
of leaks. Again, that's just one of the reasons that you should not solely rely on this package protecting your site!

