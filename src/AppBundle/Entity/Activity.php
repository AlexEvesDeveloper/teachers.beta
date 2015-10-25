<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Activity
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Activity
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="machine_name", type="string", length=255)
     */
    private $machineName;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Category", mappedBy="activity")
     */
    private $categories;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Student", inversedBy="activites")
     */
    private $students;

    /**
     * Activity constructor.
     */
    public function __construct(Category $defaultCategory)
    {
        $this->categories = new ArrayCollection();
        $this->students = new ArrayCollection();

        $this->addCategory($defaultCategory);
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return Activity
     */
    public function setTitle($title)
    {
        $this->title = $title;
        $this->setMachineName($title);

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getMachineName()
    {
        return $this->machineName;
    }

    /**
     * Lower case, hyphen separated conversion of $title
     *
     * @param string $title
     * @return Skill
     */
    private function setMachineName($title)
    {
        $this->machineName = strtolower(str_replace(' ', '-', $title));

        return $this;
    }

    /**
     * Add category
     *
     * @param Category $category
     *
     * @return Activity
     */
    public function addCategory(Category $category)
    {
        $key = $category->getMachineName();

        if ($this->categories->containsKey($key)) {
            throw new \InvalidArgumentException('This Activity already has a category of that name');
        }

        $this->categories->set($key, $category);

        return $this;
    }

    /**
     * Remove category
     *
     * @param Category $category
     */
    public function removeCategory(Category $category)
    {
        $this->categories->removeElement($category);
    }

    /**
     * Get categories
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * Add student
     *
     * @param Student $student
     *
     * @return Activity
     */
    public function addStudent(Student $student)
    {
        $key = $student->getId();

        if ($this->students->containsKey($key)) {
            throw new \InvalidArgumentException('The Student has already joined this Activity');
        }

        $this->students->set($key, $student);

        return $this;
    }

    /**
     * Remove student
     *
     * @param Student $student
     */
    public function removeStudent(Student $student)
    {
        $this->students->removeElement($student);
    }

    /**
     * Get students
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getStudents()
    {
        return $this->students;
    }

    /**
     * A Skill that is applied directly to an Activity is automatically assigned to the default Category
     *
     * @param Skill $skill
     * @return $this
     */
    public function addSkill(Skill $skill)
    {
        $this->categories->get('default')->addSkill($skill);

        return $this;
    }
}
