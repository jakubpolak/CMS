<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;

/**
 * @author Jakub Polák, Jana Poláková
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 */
class User implements AdvancedUserInterface, \Serializable, Entity {
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
     * @ORM\Column(name="username", type="string", length=100, unique=true)
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=100)
     */
    private $password;

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
        $this->salt = md5(uniqid(null, true));
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
     * Get salt
     *
     * @return null
     */
    public function getSalt() {
        return null;
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
     * Set username.
     *
     * @return User
     */
    public function setUsername($username) {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username.
     *
     * @return string
     */
    public function getUsername() {
        return $this->username;
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
            $this->username,
            $this->password,
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
            $this->username,
            $this->password,
            ) = unserialize($serialized);
    }
}

