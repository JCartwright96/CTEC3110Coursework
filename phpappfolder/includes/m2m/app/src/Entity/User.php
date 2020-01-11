<?php
namespace M2m\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * User
 *
 * @ORM\Table(name="user_data")
 * @ORM\Entity
 */
class User
{
    /**
     * @var int
     *
     * @ORM\Column(name="auto_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="user_name", type="string", length=254, nullable=false)
     */
    private $user_name;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=254, nullable=false)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="phone_number", type="string", length=15, nullable=true)
     */
    private $phone;


    /**
     * @var string|null
     *
     * @ORM\Column(name="password", type="string", length=254, nullable=false, options={"fixed"=true})
     */
    private $password;


    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set user_name.
     *
     * @return string
     */
    public function setUserName($user_name)
    {
        $this->user_name = $user_name;

        return $this->user_name;
    }

    /**
     * Get user_name.
     *
     * @return string
     */
    public function getUserName()
    {
        return $this->user_name;
    }

    /**
     * Set password.
     *
     * @param string $password
     *
     * @return string
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this->password;
    }

    /**
     * Set phone_number.
     *
     * @param string $password
     *
     * @return string
     */
    public function setPhone($phone_number)
    {
        $this->phone = $phone_number;

        return $this->phone;
    }


    /**
     * Get password.
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Get phone_number.
     *
     * @return string|null
     */
    public function getPhone()
    {
        return $this->phone;
    }


    /**
     * Set email.
     *
     * @param string $email
     *
     * @return string
     */
    public function setEmail($email = null)
    {
        $this->email = $email;

        return $this->email;
    }

    /**
     * Get email.
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }
}
