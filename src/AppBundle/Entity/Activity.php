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
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Category", mappedBy="activity")
     */
    private $categories;

    /**
     * Activity constructor.
     */
    public function __construct(Category $defaultCategory)
    {
        $this->categories = new ArrayCollection();

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
