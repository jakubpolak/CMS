<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;

/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 */
class User implements AdvancedUserInterface, \Serializable {
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=100, unique=true)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=100)
     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column(name="salt", type="string", length=50)
     */
    private $salt;

    /**
     * @var Collection
     *
     * @ORM\ManyToMany(targetEntity="Role", inversedBy="users", cascade={"merge"})
     * @ORM\JoinTable(name="user_has_role",
     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="role_id", referencedColumnName="id")}
     * )
     */
    private $roles;

    /**
     * Constructor.
     */
    public function __construct() {
        $this->roles = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return User
     */
    public function setEmail($email) {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail() {
        return $this->email;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return User
     */
    public function setPassword($password) {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword() {
        return $this->password;
    }

    /**
     * Set salt
     *
     * @param string $salt
     * @return User
     */
    public function setSalt($salt) {
        $this->salt = $salt;

        return $this;
    }

    /**
     * Get salt
     *
     * @return string
     */
    public function getSalt() {
        return $this->salt;
    }

    /**
     * Set roles.
     *
     * @param Collection $roles roles
     */
    public function setRoles(Collection $roles) {
        $this->roles = $roles;
    }

    /**
     * Get roles.
     *
     * @return array
     */
    public function getRoles() {
        return $this->roles->toArray();
    }

    /**
     * Get username.
     *
     * @return string
     */
    public function getUsername() {
        return $this->email;
    }

    /**
     * Is account non expired.
     *
     * @return bool
     */
    public function isAccountNonExpired() {
        return true;
    }

    /**
     * Is account non locked.
     *
     * @return bool
     */
    public function isAccountNonLocked() {
        return true;
    }

    /**
     * Is credentials non expired.
     *
     * @return bool
     */
    public function isCredentialsNonExpired() {
        return true;
    }

    /**
     * Is enabled.
     *
     * @return bool
     */
    public function isEnabled() {
        return true;
    }

    /**
     * Erase credentials.
     */
    public function eraseCredentials() {

    }

    /**
     * Serialize.
     *
     * @return string
     */
    public function serialize() {
        return serialize([
            $this->id,
            $this->email,
            $this->password,
            $this->salt,
        ]);
    }

    /**
     * Unserialize.
     *
     * @param string $serialized serialized
     */
    public function unserialize($serialized) {
        list(
            $this->id,
            $this->email,
            $this->password,
            $this->salt,
            ) = unserialize($serialized);
    }
}

