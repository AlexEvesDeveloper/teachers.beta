<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Activity;
use AppBundle\Entity\Category;
use AppBundle\Entity\Skill;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadFixtures implements FixtureInterface, ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    public function load(ObjectManager $manager)
    {
        $sports = $this->loadActivities($manager);
    }

    private function loadActivities(ObjectManager $manager)
    {
        $activities = array();
        $activities['athletics'] = $this->loadAthletics($manager);

        return $activities;
    }

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

        $manager->flush();

        return $athletics;
    }

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
}