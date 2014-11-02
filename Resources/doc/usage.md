# Usage

## Scanning a file upload

Let's assume you allow your users to upload files to your website. Odds are you want to be sure those files do not contain
any malicious viruses so you want to scan those files before permanently storing them somewhere on your server.

Below is an example of what your action could look like if you used this bundle to scan the file(s):
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
            // this is the uploaded file (instance of `UploadedFile`)
            $uploadedFile = $form->get('my_upload')->getData();

            $scanner = $this->get('cl_tissue.scanner');
            $result  = $scanner->scan($uploadedFile->getRealPath());

            if ($result->hasVirus()) {
                // do something about it ?
                unlink($uploadedFile->getRealPath());

                // ... warn/troll/ban the user :)?
                //$this->addFlash('warning', 'You did something naughty!');
                //$this->banUser('...');

                // let's send 'em back where they came from!
                return $this->redirect($this->generateUrl('...'));
            }

            // proceed as normal?
            $uploadedFile->move('/path/to/some/pretty/place', 'clean_file.txt');

            // ...
        }
    }
}
```

The form used in the example above would look something like this:
```php
<?php

namespace Acme\DemoBundle\Form\Type;

use CL\Bundle\TissueBundle\Validator\Constraints\VirusFreeFile;
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
                new VirusFreeFile()
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

Note the constraint added to the `file`-field here. During validation, this will cause Tissue to first scan the uploaded
file before passing it on the regular `File` constraint (or preventing this if a virus is found). The scanner used
is determined by which service you configured in the previous chapter.

**IMPORTANT:**

Although this should be enough to keep any evil-doers from uploading viruses to this particular form, you must keep in
mind that there are many more ways to abuse uploads than just uploading a virus.

For instance, if you were to keep this file available within your web-directory after it has been uploaded, you better
make sure that there is NO CHANCE that the file may get executed by your application in one way or another.

A malicious user could simply upload a piece of PHP-code (no virus!) that will open your application up to a huge range
of leaks. Again, that's just one of the reasons that you should not solely rely on this package protecting your site!

