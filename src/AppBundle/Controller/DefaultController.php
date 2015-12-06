<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Session\Session;
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
     * @Route("/answer/{id}", name="answer")
     */
    public function showAnswerAction($id)
    {

        $query = $this->getDoctrine()->getRepository('AppBundle:Question');
        $question = $query->find($id);

        $query = $this->getDoctrine()->getRepository('AppBundle:Choice');
        $rightChoices = $query->getQuestionChoicesContent($id);

        return $this->render('default/answers.html.twig', array(
                'question'      => $question,
                'right_choices' => $rightChoices
        ));         

    }

    /**
     * @Route("/question/{id}", name="question")
     */
    public function showQuestionAction($id, Request $request)
    {
        $session = new Session();
        $notice = $session->getFlashBag();

        $query = $this->getDoctrine()->getRepository('AppBundle:Question');
        
        $question = $query->find($id);
        $form = $this->createForm( new QuestionType($question));
        $form->handleRequest($request);

        if ($form->isSubmitted()) {

            $currentChoices = $form["choices"]->getViewData();

            $query = $this->getDoctrine()->getRepository('AppBundle:Choice');
            $rightChoices = $query->getQuestionChoices($id);

            if (is_object(current($currentChoices))) {
                $currentChoices = array_map('current',$form['choices']->getViewData());
            }

            
            (!empty($currentChoices) && !array_diff($currentChoices, $rightChoices))
                ? $notice->add('success', "Congratulation, it's a right answer!")
                : $notice->add('error', "Sorry, this is wrong answer.");

            return $this->redirectToRoute('random');
        }
        
        return $this->render('default/questions.html.twig', array(
                'question'  => $question,
                'form'      => $form->createView(),
        ));        
    }

    /**
     * @Route("/random", name="random")
     */
    public function randomAction(Request $request)
    {   
        $query = $this->getDoctrine()->getRepository('AppBundle:Question');
        $questionId = mt_rand(1, $query->size());
        return $this->redirectToRoute('question', ["id"=> $questionId]);
    }
}
