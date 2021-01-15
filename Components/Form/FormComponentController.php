<?php

namespace Akyos\BuilderBundle\Components\Form;

use Akyos\BuilderBundle\Interfaces\ComponentInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Akyos\FormBundle\Controller\ContactFormFieldController;
use Symfony\Component\HttpFoundation\RequestStack;


class FormComponentController extends AbstractController implements ComponentInterface
{
    protected $form;
    private $request;

    public function __construct(ContactFormFieldController $form, RequestStack $requestStack)
    {
        $this->request = $requestStack;
        $this->form = $form;
    }

    public function getTemplateName()
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