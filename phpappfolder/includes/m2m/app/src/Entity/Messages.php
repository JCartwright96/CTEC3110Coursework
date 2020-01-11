<?php
namespace M2m\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * User
 *
 * @ORM\Table(name="message_data")
 * @ORM\Entity
 */
class Messages
{
    /**
     * @var integer
     *
     * @ORM\Column(name="message_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="phone_number", type="string", length=15, nullable=false)
     */
    private $phone;

    /**
     * @var integer
     *
     * @ORM\Column(name="switch_01", type="integer", length=1, nullable=false)
     */
    private $switch_01;

    /**
     * @var integer
     *
     * @ORM\Column(name="switch_02", type="integer", length=1, nullable=false)
     */
    private $switch_02;

    /**
     * @var integer
     *
     * @ORM\Column(name="switch_03", type="integer", length=1, nullable=false)
     */
    private $switch_03;

    /**
     * @var integer
     *
     * @ORM\Column(name="switch_04", type="integer", length=1, nullable=false)
     */
    private $switch_04;

    /**
     * @var string
     *
     * @ORM\Column(name="fan", type="string", nullable=false)
     */
    private $fan;

    /**
     * @var integer
     *
     * @ORM\Column(name="heater", type="integer", length=3, nullable=false)
     */
    private $heater;

    /**
     * @var integer
     *
     * @ORM\Column(name="keypad", type="integer", length=1, nullable=false)
     */
    private $keypad;

    /**
     * @var datetime
     *
     * @ORM\Column(name="receivedtime", type="datetime", nullable=false)
     */
    private $receivedtime;

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
     * Get phone_number.
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Get switch_01.
     *
     * @return int
     */
    public function getSwitch01()
    {
        return $this->switch_01;
    }

    /**
     * Get switch_02.
     *
     * @return int
     */
    public function getSwitch02()
    {
        return $this->switch_02;
    }

    /**
     * Get switch_03.
     *
     * @return int
     */
    public function getSwitch03()
    {
        return $this->switch_03;
    }

    /**
     * Get switch_04.
     *
     * @return int
     */
    public function getSwitch04()
    {
        return $this->switch_04;
    }

    /**
     * Get fan.
     *
     * @return string
     */
    public function getFan()
    {
        return $this->fan;
    }

    /**
     * Get heater.
     *
     * @return int
     */
    public function getHeater()
    {
        return $this->heater;
    }

    /**
     * Get keypad.
     *
     * @return int
     */
    public function getKeypad()
    {
        return $this->keypad;
    }

    /**
     * Get receivedtime.
     *
     * @return int
     */
    public function getReceivedTime()
    {
        return $this->receivedtime;
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
     * Set switch_01.
     *
     * @param int $switch_01
     *
     * @return int
     */
    public function setSwitch01($switch_01)
    {
        $this->switch_01 = $switch_01;

        return $this->switch_01;
    }

    /**
     * Set switch_02.
     *
     * @param int $switch_02
     *
     * @return int
     */
    public function setSwitch02($switch_02)
    {
        $this->switch_02 = $switch_02;

        return $this->switch_02;
    }

    /**
     * Set switch_03.
     *
     * @param int $switch_03
     *
     * @return int
     */
    public function setSwitch03($switch_03)
    {
        $this->switch_03 = $switch_03;

        return $this->switch_03;
    }

    /**
     * Set switch_04.
     *
     * @param int $switch_04
     *
     * @return int
     */
    public function setSwitch04($switch_04)
    {
        $this->switch_04 = $switch_04;

        return $this->switch_04;
    }

    /**
     * Set fan.
     *
     * @param string fan
     *
     * @return string
     */
    public function setFan($fan)
    {
        $this->fan = $fan;

        return $this->fan;
    }

    /**
     * Set heater.
     *
     * @param int heater
     *
     * @return int
     */
    public function setHeater($heater)
    {
        $this->heater = $heater;

        return $this->heater;
    }

    /**
     * Set keypad.
     *
     * @param int keypad
     *
     * @return int
     */
    public function setKeypad($keypad)
    {
        $this->keypad = $keypad;

        return $this->keypad;
    }

    /**
     * Set receivedtime.
     *
     * @return string
     */
    public function setReceivedTime($receivedtime)
    {
        $this->receivedtime = $receivedtime;

        return $this->receivedtime;
    }
}
