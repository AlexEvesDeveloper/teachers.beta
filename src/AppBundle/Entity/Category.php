<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Category
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Category
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
     */
    private $machineName;

    /**
     * @var Activity
     * @ORM\ManyToOne(targetEntity="Activity", inversedBy="categories")
     */
    private $activity;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Skill", mappedBy="categories")
     */
    private $skills;

    /**
     * @ORM\OneToMany(targetEntity="Category", mappedBy="parent")
     **/
    private $children;

    /**
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="children")
     **/
    private $parent;

    /**
     * Activity constructor.
     */
    public function __construct()
    {
        $this->skills = new ArrayCollection();
        $this->children = new ArrayCollection();
    }

    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Automatically set $machineName at the same time
     *
     * @param string $title
     * @return Category
     */
    public function setTitle($title)
    {
        $this->title = $title;
        $this->setMachineName($title);

        return $this;
    }

    /**
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
     * @return Category
     */
    private function setMachineName($title)
    {
        $this->machineName = strtolower(str_replace(' ', '-', $title));

        return $this;
    }

    /**
     * @param Activity $activity
     * @return Category
     */
    public function setActivity(Activity $activity = null)
    {
        $this->activity = $activity;

        return $this;
    }

    /**
     * @return Activity
     */
    public function getActivity()
    {
        return $this->activity;
    }

    /**
     * @param Skill $skill
     * @return Activity
     */
    public function addSkill(Skill $skill)
    {
        $key = $skill->getMachineName();

        if ($this->skills->containsKey($key)) {
            throw new \InvalidArgumentException('This Category already has a skill of that name');
        }

        $this->skills->set($key, $skill);

        return $this;
    }

    /**
     * @param Skill $skill
     */
    public function removeSkill(Skill $skill)
    {
        $this->skills->removeElement($skill);
    }

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSkills()
    {
        return $this->skills;
    }

    /**
     * @param Category $category
     * @return $this
     */
    public function addChild(Category $category)
    {
        $key = $category->getMachineName();

        if ($this->children->containsKey($key)) {
            throw new \InvalidArgumentException('This Category already has a child Category of that name');
        }

        $this->children->set($key, $category);

        return $this;
    }

    /**
     * @param Category $category
     */
    public function removeChild(Category $category)
    {
        $this->children->removeElement($category);
    }

    /**
     * @return ArrayCollection
     */
    public function getChildren()
    {
        return $this->children;
    }

    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @param Category $category
     * @return $this
     */
    public function setParent(Category $category)
    {
        $this->parent = $category;

        return $this;
    }
}
