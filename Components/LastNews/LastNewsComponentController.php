<?php
        
namespace Akyos\BuilderBundle\Components\LastNews;

use Akyos\BuilderBundle\Interfaces\ComponentInterface;
use Akyos\CoreBundle\Entity\Post;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class LastNewsComponentController extends AbstractController implements ComponentInterface
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }


    public function getTemplateName()
    {
        return '@BuilderComponents/LastNews/lastNews_component.html.twig';
    }

    public function getParameters($params = null)
    {
        $params['news'] = $this->em->getRepository(Post::class)->findBy(['published' => true], ['createdAt' => 'DESC'], 3);
        return $params;
    }
}