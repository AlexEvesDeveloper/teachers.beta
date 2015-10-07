<?php

namespace AppBundle\Tests\Entity;

use AppBundle\Entity\Activity;
use AppBundle\Entity\Category;
use AppBundle\Entity\Skill;

class CategoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function creates_a_machine_name_when_its_title_is_set()
    {
        $category = new Category();

        $this->assertNull($category->getMachineName());
        $category->setTitle('foo');
        $this->assertNotNull($category->getMachineName());
    }

    /**
     * @test
     */
    public function machine_name_is_title_converted_to_lower_case_and_hyphens()
    {
        $category = new Category();

        // 'foo' => 'foo'
        $category->setTitle('foo');
        $this->assertEquals('foo', $category->getMachineName());

        // 'FooBar' => 'foobar'
        $category->setTitle('FooBar');
        $this->assertEquals('foobar', $category->getMachineName());

        // 'Foo Bar Baz' => 'foo-bar-baz'
        $category->setTitle('Foo Bar Baz');
        $this->assertEquals('foo-bar-baz', $category->getMachineName());
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     */
    public function throws_an_exception_when_adding_a_skill_whose_name_already_exists_in_the_category()
    {
        $category = new Category();

        $skill = (new Skill())
            ->setTitle('foo');

        $duplicate = (new Skill())
            ->setTitle('foo');

        $category->addSkill($skill);
        $category->addSkill($duplicate);
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     */
    public function throws_an_exception_when_adding_a_child_whose_name_already_exists_in_its_children()
    {
        $category = new Category();

        $child = (new Category())
            ->setTitle('foo');

        $duplicate = (new Category())
            ->setTitle('foo');

        $category->addChild($child);
        $category->addChild($duplicate);
    }
}