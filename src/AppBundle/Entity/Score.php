<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Score
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Entity\Repository\ScoreRepository")
 */
class Score
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
     * @var integer
     *
     * @ORM\Column(name="value", type="smallint")
     */
    private $value;

    /**
     * @var Student
     *
     * @ORM\ManyToOne(targetEntity="Student")
     */
    private $student;

    /**
     * @var Activity
     *
     * @ORM\ManyToOne(targetEntity="Activity")
     */
    private $activity;

    /**
     * @var Skill
     *
     * @ORM\ManyToOne(targetEntity="Skill")
     */
    private $skill;

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
     * Set value
     *
     * @param integer $value
     *
     * @return Score
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return integer
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @return Student
     */
    public function getStudent()
    {
        return $this->student;
    }

    /**
     * @param Student $student
     * @return Score
     */
    public function setStudent(Student $student)
    {
        $this->student = $student;
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
     * @param Activity $activity
     * @return Score
     */
    public function setActivity(Activity $activity)
    {
        $this->activity = $activity;
        return $this;
    }

    /**
     * @return Skill
     */
    public function getSkill()
    {
        return $this->skill;
    }

    /**
     * @param Skill $skill
     * @return Score
     */
    public function setSkill(Skill $skill)
    {
        $this->skill = $skill;
        return $this;
    }
}

