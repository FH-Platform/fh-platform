<?php

namespace FHPlatform\Component\DoctrineToEs\Tests\Util\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\Bill\Bill;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\Location\Location;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\Role\Role;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\Setting\Setting;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\Trait\AllTypesTrait;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\Trait\IdTrait;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\User\Address;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\User\Invite;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\User\UserApiToken;

#[ORM\Entity]
class User
{
    use IdTrait;
    use AllTypesTrait;

    // ALL_TYPES relations testing

    // One-To-One, Unidirectional
    #[ORM\OneToOne(targetEntity: UserApiToken::class)]
    private ?UserApiToken $userApiToken = null;

    // One-To-One, Bidirectional
    #[ORM\OneToOne(inversedBy: 'user', targetEntity: Setting::class)]
    private ?Setting $setting = null;

    // One-To-One, Bidirectional-Self-referencing
    #[ORM\OneToOne(targetEntity: User::class, inversedBy: 'bestFriend')]
    private ?User $bestFriend = null;

    // Many-To-One, Unidirectional
    #[ORM\ManyToOne(targetEntity: Address::class)]
    private ?Address $address = null;

    // Many-To-One, Bidirectional
    #[ORM\ManyToOne(targetEntity: Location::class, inversedBy: 'users')]
    private ?Location $location = null;

    // Many-To-One, Bidirectional-Self-referencing
    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'students')]
    private ?User $mentor = null;

    // One-To-Many, Unidirectional

    // One-To-Many, Bidirectional
    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Bill::class)]
    private Collection $bills;


    // One-To-Many, Bidirectional-Self-referencing
    #[ORM\OneToMany(mappedBy: 'mentor', targetEntity: User::class)]
    private Collection $students;

    // One-To-Many, Self-referencing

    // Many-To-Many, Unidirectional
    #[ORM\ManyToMany(targetEntity: Invite::class)]
    private Collection $invites;

    // Many-To-Many, Bidirectional
    #[ORM\ManyToMany(targetEntity: Role::class, inversedBy: 'users')]
    private Collection $roles;

    // Many-To-Many, Self-referencing
    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'myFriends')]
    private Collection $friendsWithMe;

    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'friendsWithMe')]
    private Collection $myFriends;

    // ALL_TYPES relations testing

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $nameTestGetterNotSet;

    #[ORM\OneToOne]
    private ?Setting $settingTestTargetEmpty = null;

    #[ORM\OneToOne(inversedBy: 'user2', targetEntity: Setting::class)]
    private ?Setting $settingTestGetterEmpty = null;

    public function __construct()
    {
        $this->bills = new ArrayCollection();

        $this->invites = new ArrayCollection();
        $this->roles = new ArrayCollection();
        $this->myFriends = new ArrayCollection();
        $this->friendsWithMe = new ArrayCollection();
    }

    public function addRole(Role $role): self
    {
        $this->roles->add($role);

        return $this;
    }

    public function getUserApiToken(): ?UserApiToken
    {
        return $this->userApiToken;
    }

    public function setUserApiToken(?UserApiToken $userApiToken): void
    {
        $this->userApiToken = $userApiToken;
    }

    public function getBestFriend(): ?User
    {
        return $this->bestFriend;
    }

    public function setBestFriend(?User $bestFriend): void
    {
        $this->bestFriend = $bestFriend;
    }

    public function getBills(): Collection
    {
        return $this->bills;
    }

    public function setBills(Collection $bills): void
    {
        $this->bills = $bills;
    }

    public function getAddress(): ?Address
    {
        return $this->address;
    }

    public function setAddress(?Address $address): void
    {
        $this->address = $address;
    }

    public function getLocation(): ?Location
    {
        return $this->location;
    }

    public function setLocation(?Location $location): void
    {
        $this->location = $location;
    }

    public function getMentor(): ?User
    {
        return $this->mentor;
    }

    public function setMentor(?User $mentor): void
    {
        $this->mentor = $mentor;
    }

    public function getInvites(): Collection
    {
        return $this->invites;
    }

    public function setInvites(Collection $invites): void
    {
        $this->invites = $invites;
    }

    public function getRoles(): Collection
    {
        return $this->roles;
    }

    public function setRoles(Collection $roles): void
    {
        $this->roles = $roles;
    }

    public function getFriendsWithMe(): Collection
    {
        return $this->friendsWithMe;
    }

    public function setFriendsWithMe(Collection $friendsWithMe): void
    {
        $this->friendsWithMe = $friendsWithMe;
    }

    public function getMyFriends(): Collection
    {
        return $this->myFriends;
    }

    public function setMyFriends(Collection $myFriends): void
    {
        $this->myFriends = $myFriends;
    }

    public function setSetting(?Setting $setting): void
    {
        $this->setting = $setting;
    }

    public function getSetting(): ?Setting
    {
        return $this->setting;
    }
}
