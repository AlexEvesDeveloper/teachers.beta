<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Activity;
use AppBundle\Entity\Category;
use AppBundle\Entity\Skill;
use AppBundle\Entity\Student;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use PUGX\MultiUserBundle\Doctrine\UserManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class LoadFixtures
 * @package AppBundle\DataFixtures\ORM
 */
class LoadFixtures implements FixtureInterface, ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $userManager = $this->container->get('pugx_user_manager');
        $students = $this->loadStudents($userManager);



        // When a Student is added to an Activity:
        //  Create new Score objects for each Skill inside that Activity:
        //      $score->setStudent($student);
        //      $score->setSkill($skill)
        //  Add the Score to the Student
        //      $student->addScore($score)
        //  From that single Score for a Student, it must know what Skill it belongs to
        //  I want to get all Activities for a Student, and get each Score inside that Activity, and also report the Skill/Category(s) that it belongs to
        //      $activities = $student->getActivities()
        //      foreach($activities as $activity) {
        //          $score = $scoreRepo->findOneBy(array('student' => $student, 'activity' => $activity));
        //      }
        //
        //  I always want to get the all Scores for an Activity
        //    $categories = $activity->getCategories();
        //
        //    $scores = $activity->getScores()
        //    foreach($scores as score) {
        //    }
        //

        $activities = $this->loadActivities($manager);

        $this->addStudentsToActivites($manager, $students, $activities);

        $manager->flush();
    }

    /**
     * @param UserManager $manager
     * @return array
     */
    private function loadStudents(UserManager $manager)
    {
        $discriminator = $this->container->get('pugx_user.manager.user_discriminator');
        $discriminator->setClass('AppBundle\Entity\Student');

        $students = array();
        for ($i = 1; $i < 4; $i++) {
            $student = $manager->createUser();
            $student->setFirstName(sprintf('Student %d', $i));
            $student->setLastName('Surname');
            $student->setUsername(uniqid());
            $student->setPlainPassword('password');
            $student->setEmail(sprintf('student%d@test.com', $i));
            $student->setEnabled(true);
            $student->setRoles(array('ROLE_STUDENT'));
            $manager->updateUser($student);
            $students[] = $student;
        }

        return $students;
    }

    /**
     * @param ObjectManager $manager
     * @return array
     */
    private function loadActivities(ObjectManager $manager)
    {
        $activities = array();
        $activities['athletics'] = $this->loadAthletics($manager);

        return $activities;
    }

    /**
     * @param ObjectManager $manager
     * @return Activity
     */
    private function loadAthletics(ObjectManager $manager)
    {
        $defaultCategory = (new Category())->setTitle('default');
        $manager->persist($defaultCategory);

        $athletics = new Activity($defaultCategory);
        $athletics->setTitle('Athletics');

        // Athletics::Distances
        $distances = (new Category())->setTitle('Distances')->setActivity($athletics);
        $manager->persist($distances);
        $athletics->addCategory($distances);
        // Athletics::Distances::Throws
        $throws = (new Category())->setTitle('Throws')->setParent($distances);
        $manager->persist($throws);
        $distances->addChild($throws);
        // Athletics::Distances::Throws::Discus
        $discus = (new Skill())->setTitle('Discus')->setCategory($throws);
        $manager->persist($discus);
        $throws->addSkill($discus);
        // Athletics::Distances::Throws::Shot
        $shot = (new Skill())->setTitle('Shot')->setCategory($throws);
        $manager->persist($shot);
        $throws->addSkill($shot);
        // Athletics::Distances::Throws::Javelin
        $javelin = (new Skill())->setTitle('Javelin')->setCategory($throws);
        $manager->persist($javelin);
        $throws->addSkill($javelin);
        // Athletics::Distances::Jumps
        $jumps = (new Category())->setTitle('Jumps')->setParent($distances);
        $manager->persist($jumps);
        $distances->addChild($jumps);
        // Athletics::Distances::Jumps::Long
        $long = (new Skill())->setTitle('Long')->setCategory($jumps);
        $manager->persist($long);
        $jumps->addSkill($long);
        // Athletics::Distances::Jumps::High
        $high = (new Skill())->setTitle('High')->setCategory($jumps);
        $manager->persist($high);
        $jumps->addSkill($high);
        // Athletics::Distances::Jumps::Triple
        $triple = (new Skill())->setTitle('Triple')->setCategory($jumps);
        $manager->persist($athletics);
        $manager->persist($triple);
        $jumps->addSkill($triple);

        // Athletics::Technique
        $technique = (new Skill())->setTitle('Technique')->setCategory($athletics->getCategories()->get('default'));
        $manager->persist($technique);
        $athletics->addSkill($technique);

        $manager->persist($athletics);

        return $athletics;
    }

    private function addStudentsToActivites(ObjectManager $manager, array $students, array $activities)
    {
        foreach($students as $student) {
            foreach($activities as $activity) {
                $student->addActivity($activity);
                $activity->addStudent($student);

                $manager->persist($activity);
                $manager->persist($student);
            }
        }
    }

    /**
     * @param ContainerInterface|null $container
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
}