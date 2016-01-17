<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Form\Type\QuestionType;
use AppBundle\Form\Type\QuestionQuizType;
use AppBundle\Helper\Image;

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
        $query = $this->getDoctrine()->getRepository('AppBundle:Choice');
        $form = $this->createForm(new QuestionType($question, $query));
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $currentChoices = $form["choices"]->getViewData();
            if (is_array($currentChoices)) {
                $currentChoices = array_map('current' ,$currentChoices);
            } else {
                $currentChoices = ($currentChoices) ? array($currentChoices->getId()) : '';
            }
            $rightChoices = $query->getQuestionChoices($id);

            if($form->get('next')->isClicked()) {
                return $this->redirectToRoute('random');
            }

            (!array_diff($rightChoices, $currentChoices))
                ? $notice->add('success', "Congratulation, it's a right answer!")
                : $notice->add('error', "Sorry, this is wrong answer.");

            return $this->redirect($this->generateUrl('question', array('id' => $id)));
        }

        $imageHelper = new Image($question, $this->get('kernel'));

        return $this->render(
            'default/questions.html.twig', 
            array(
                'question'  => $question,
                'form'      => $form->createView(),
                'image_path' => $imageHelper->getImageAssetPath()
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

    /**
     * @Route("/quiz", name="quiz")
     */
    public function quizAction(Request $request)
    {
        $session = new Session();

        $query = $this->getDoctrine()->getRepository('AppBundle:Question');

        if($session->get('questionsForAnswer') !== null && count($session->get('questionsForAnswer')) == 0) {
            $allQuestions = $session->get('countOfAllAnswers');
            $rightAnswers = $session->get('countOfRightAnswers');
            $session->clear();
            return new Response("You answer correctly $rightAnswers questions out of $allQuestions");
        }
        if (!$session->get('questionsForAnswer')) {
            $questionsForAnswer = range(1, $query->size());
            //$questionsForAnswer = range(1, 3);
            $session->set('questionsForAnswer', $questionsForAnswer);
            $session->set('countOfAllAnswers', $query->size());
            $session->set('countOfRightAnswers', 0);
        }

        $ids = $session->get('questionsForAnswer');

        if(!$session->get('currentQuestion')) {
            $questionKey = array_rand($ids);
            $id = $ids[$questionKey];
            $session->set('currentQuestion', $id);
        }

        $id = $session->get('currentQuestion');

        if(($key = array_search($id, $ids)) !== false) {
            unset($ids[$key]);
        }

        dump(count($session->get('questionsForAnswer')));
        dump($session->get('countOfRightAnswers'));
        dump($id);

        $question = $query->find($id);
        $query = $this->getDoctrine()->getRepository('AppBundle:Choice');
        $form = $this->createForm(new QuestionQuizType($question, $query));
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $session->set('questionsForAnswer', $ids);
            $currentChoices = $form["choices"]->getViewData();
            if (is_array($currentChoices)) {
                $currentChoices = array_map('current' ,$currentChoices);
            } else {
                $currentChoices = ($currentChoices) ? array($currentChoices->getId()) : array();
            }

            $rightChoices = $query->getQuestionChoices($id);

            if (!array_diff($rightChoices, $currentChoices)) {
                $rightAnswers = $session->get('countOfRightAnswers');
                $rightAnswers += 1;
                $session->set('countOfRightAnswers', $rightAnswers);
            }

            $session->remove('currentQuestion');

            return $this->redirect($this->generateUrl('quiz'));
        }

        $imageHelper = new Image($question, $this->get('kernel'));

        return $this->render(
            'default/questions.html.twig', 
            array(
                'question'  => $question,
                'form'      => $form->createView(),
                'image_path' => $imageHelper->getImageAssetPath()
            ));
    }

    /**
     * @Route("/quiz/new")
     */
    public function newQuizAction()
    {
        $session = new Session();
        $session->clear();
        return $this->redirectToRoute('quiz');
    }
}
