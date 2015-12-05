<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Form\Type\QuestionType;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', array(
            'base_dir' => realpath($this->container->getParameter('kernel.root_dir').'/..'),
        ));
    }

    /**
     * @Route("/random", name="random")
     */
    public function randomAction(Request $request)
    {
        $query = $this->getDoctrine()->getRepository('AppBundle:Question');
        $questionNum = mt_rand(1, $query->size());
        $question = $query->find($questionNum);
        $form = $this->createForm( new QuestionType($question));

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $query = $this->getDoctrine()->getRepository('AppBundle:Choice');
            $rightChoices = $query->getQuestionChoices($question->getId());
            dump($rightChoices);
            $data = $form["choices"]->getViewData();
            var_dump($data);
            //return $this->redirectToRoute('random');
        }

        return $this->render('default/random.html.twig', array(
                'question'  =>  $question,
                'form'      =>  $form->createView()
        ));
    }
}
