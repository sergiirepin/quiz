<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Cookie;
use AppBundle\Form\Type\QuestionType;
use AppBundle\Form\Type\QuestionQuizType;
use AppBundle\Helper\Image;
use AppBundle\Entity\Question;

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
     * @Route("/answer/{id}", name="answer", defaults={"id" = 1})
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
    public function showQuestionAction($id=null, Request $request)
    {
        $session = new Session();
        $notice = $session->getFlashBag();
        $query = $this->getDoctrine()->getRepository('AppBundle:Question');
        $size = $query->size();
        $response = new Response();

        if(!$id) {
            $response->headers->clearCookie('checked');
            $response->sendHeaders();
            return $this->redirect($this->generateUrl('question', array('id' => mt_rand(1, $size))));
        }

        $question = $query->find($id);
        $query = $this->getDoctrine()->getRepository('AppBundle:Choice');
        $form = $this->createForm(new QuestionType($question, $query, $size));
        $form->handleRequest($request);

        if($form->get('next')->isClicked()) {
            $id = ($form->get('questionId')->getViewData())
                ? $form->get('questionId')->getViewData()
                : mt_rand(1, $size);
            return $this->redirect($this->generateUrl('question', array('id' => $id)));
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $currentChoices = $form["choices"]->getViewData();
            if (is_array($currentChoices)) {
                $currentChoices = array_map('current' ,$currentChoices);
            } else {
                $currentChoices = ($currentChoices) ? array($currentChoices->getId()) : '';
            }
            $rightChoices = $query->getQuestionChoices($id);

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
     * @Route("/quiz", name="quiz")
     */
    public function quizAction(Request $request)
    {
        $session = new Session();
        if(!$session->get('startTime')) {
            $session->set('startTime', time());
        }

        $query = $this->getDoctrine()->getRepository('AppBundle:Question');
        $questionsCount = $query->size();

        if($this->get('quiz.validation')->isCompleted()
            || $this->get('quiz.validation')->timeIsUp()) {
            return $this->redirect($this->generateUrl('result'));
        }

        if (!$session->get('questionsForAnswer')) {
            $questionsForAnswer = range(1, $query->size());
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

        if ($form->isSubmitted()  && $form->isValid()) {
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

        $progress['completed'] = round(($questionsCount - count($session->get('questionsForAnswer')))*100/$questionsCount);
        $progress['remaining'] = round(100 - $this->get('quiz.validation')->getElapsedTime()*100/$this->get('quiz.validation')->getQuizOverallTime());

        return $this->render(
            'default/quiz.html.twig',
            array(
                'question'      => $question,
                'form'          => $form->createView(),
                'image_path'    => $imageHelper->getImageAssetPath(),
                'elapsedTime'   => $this->get('quiz.validation')->getElapsedTime(),
                'progress'      => $progress,
                'quizOverallTime'   => $this->get('quiz.validation')->getQuizOverallTime()
            ));
    }

    /**
     * @Route("/quiz/new", name="new-quiz")
     */
    public function newQuizAction()
    {
        $session = new Session();
        $session->clear();
        return $this->redirectToRoute('quiz');
    }

    /**
     * @Route("/quiz/result", name="result")
     */
    public function resultAction()
    {
        $session = new Session();
        $allQuestions = (int)$session->get('countOfAllAnswers');
        $rightAnswers = (int)$session->get('countOfRightAnswers');
        $startTimestamp = $session->get('startTime');
        $startTime = new \DateTime();
        $startTime->setTimestamp($startTimestamp);
        $currentTime = new \DateTime();
        $result['answers'] = ($allQuestions)
            ? number_format($rightAnswers*100/$allQuestions, 1)
            : 0;
        $result['time'] = $currentTime->diff($startTime)->format('%hh:%im:%ss');
        $session->clear();
        return $this->render(
            'default/result.html.twig',
            array(
                'result' => $result
            )
        );
    }
}
