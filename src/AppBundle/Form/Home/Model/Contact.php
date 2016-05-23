<?php

namespace AppBundle\Form\Home\Model;

/**
 * Class Contact
 *
 * @package AppBundle\Form\Home\Model
 */
class Contact {
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $phone;

    /**
     * @var string
     */
    private $email;

    /**
     * @var string
     */
    private $message;

    /**
     * Get name.
     *
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Set name.
     *
     * @param string $name
     * @return $this
     */
    public function setName($name): self{
        $this->name = $name;

        return $this;
    }

    /**
     * Get phone.
     *
     * @return string
     */
    public function getPhone() {
        return $this->phone;
    }

    /**
     * Set phone.
     *
     * @param string $phone
     */
    public function setPhone($phone): self{
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get email.
     *
     * @return string
     */
    public function getEmail() {
        return $this->email;
    }

    /**
     * Set email.
     *
     * @param string $email
     * @return $this
     */
    public function setEmail($email): self{
        $this->email = $email;

        return $this;
    }

    /**
     * Get message.
     *
     * @return string
     */
    public function getMessage() {
        return $this->message;
    }

    /**
     * Set message.
     *
     * @param string $message
     * @return $this
     */
    public function setMessage($message): self{
        $this->message = $message;

        return $this;
    }
}