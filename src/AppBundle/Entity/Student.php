<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use PUGX\MultiUserBundle\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity
 * @ORM\Table(name="student")
 * @UniqueEntity(fields = "username", targetClass = "AppBundle\Entity\User", message="fos_user.username.already_used")
 * @UniqueEntity(fields = "email", targetClass = "AppBundle\Entity\User", message="fos_user.email.already_used")
 */
class Student extends User
{
    const ROLE_DEFAULT = 'ROLE_STUDENT';

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Activity", mappedBy="students")
     */
    private $activities;

    /**
     * Student constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->activities = new ArrayCollection();
    }

    /**
     * Add activity
     *
     * @param Activity $activity
     * @return Student
     */
    public function addActivity(Activity $activity)
    {
        $key = $activity->getMachineName();

        if ($this->activities->containsKey($key)) {
            throw new \InvalidArgumentException('This Student already belongs to this Activity');
        }

        $this->activities->set($key, $activity);

        return $this;
    }

    /**
     * Remove activity
     *
     * @param Activity $activity
     */
    public function removeCategory(Activity $activity)
    {
        $this->activities->removeElement($activity);
    }

    /**
     * Get activities
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getActivities()
    {
        return $this->activities;
    }
}