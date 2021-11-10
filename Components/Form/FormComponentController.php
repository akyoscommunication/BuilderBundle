<?php

namespace Akyos\BuilderBundle\Components\Form;

use Akyos\BuilderBundle\Interfaces\ComponentInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Akyos\FormBundle\Controller\ContactFormFieldController;


class FormComponentController extends AbstractController implements ComponentInterface
{
    protected ContactFormFieldController $form;

    public function __construct(ContactFormFieldController $form)
    {
        $this->form = $form;
    }

    public function getTemplateName(): string
    {
        return '@BuilderComponents/Form/form_component.html.twig';
    }

    public function getParameters($params = null)
    {
        $id = $params['values']['idform'];

        if ($id) {
            $params['formView'] = $this->form->renderContactForm($id, [], 'Envoyer')->getContent();
        }

        return $params;
    }
}