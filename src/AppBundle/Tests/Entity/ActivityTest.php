<?php

namespace AppBundle\Tests\Entity;

use AppBundle\Entity\Activity;
use AppBundle\Entity\Category;
use AppBundle\Entity\Skill;

class ActivityTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function assigns_the_given_default_category_on_creation()
    {
        $defaultCategory = (new Category())->setTitle('default');
        $activity = new Activity($defaultCategory);

        $this->assertInstanceOf('AppBundle\Entity\Category', $activity->getCategories()->get('default'));
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     */
    public function throws_an_exception_when_adding_a_category_whose_name_already_exists_in_the_activity()
    {
        $defaultCategory = (new Category())->setTitle('default');
        $activity = new Activity($defaultCategory);

        $duplicateCategory = new Category();
        $duplicateCategory->setTitle('default');

        $activity->addCategory($duplicateCategory);
    }

    /**
     * @test
     */
    public function assigns_a_direct_skill_to_its_default_category()
    {
        $defaultCategory = (new Category())->setTitle('default');
        $activity = new Activity($defaultCategory);

        $skill = (new Skill())
            ->setTitle('throwing')
            ->setCategory($activity->getCategories()->get('default'));

        $activity->addSkill($skill);

        $this->assertSame($activity->getCategories()->get('default'), $skill->getCategory());
    }
}