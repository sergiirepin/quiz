<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use AppBundle\Entity\Question;
use AppBundle\Entity\Choice;

class LoadFixtures implements FixtureInterface, ContainerAwareInterface
{

    private $container;


    public function load(ObjectManager $manager)
    {
        $this->loadQuestions($manager);
        $this->loadChoices($manager);
    }

    public function loadQuestions(ObjectManager $manager)
    {
        $path = $this->container->get('kernel')->getRootDir() . '/../web/fixtures/' . 'questions.csv';
        $csvData = array_map('str_getcsv', file($path));
        array_shift($csvData);

        foreach($csvData as $csvQuestion){
            $question = new Question();
            $question->setQuestion($csvQuestion[1]);
            $manager->persist($question);
        }
        $manager->flush();
    }

    public function loadChoices(ObjectManager $manager)
    {
        $path = $this->container->get('kernel')->getRootDir() . '/../web/fixtures/' . 'choices.csv';
        $csvData = array_map('str_getcsv', file($path));
        array_shift($csvData);
        $repository = $manager->getRepository('AppBundle:Question');

        foreach($csvData as $csvChoice){
            $question = $repository->find($csvChoice[2]);
            $choice = new Choice();
            $choice->setQuestion($question)
                    ->setIsRight($csvChoice[3])
                    ->setChoice($csvChoice[4])
                    ->setChoiceMark($csvChoice[1]);

            $manager->persist($choice);
        }
        $manager->flush();
    }

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

}