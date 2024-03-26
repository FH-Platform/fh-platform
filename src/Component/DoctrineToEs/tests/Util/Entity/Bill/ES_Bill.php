<?php

namespace FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\Bill;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\User;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\Trait\AllTypesTrait;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\Trait\IdTrait;

#[ORM\Entity]
class ES_Bill
{
    use IdTrait;
    use AllTypesTrait;

    // Many-To-One (back)
    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'bills')]
    private ?User $user = null;

    // One-To-One
    #[ORM\OneToOne(mappedBy: 'bill', targetEntity: ES_BillMain::class)]
    private ?ES_BillMain $billMain = null;

    // Many-To-One
    #[ORM\ManyToOne(targetEntity: ES_BillGroup::class, inversedBy: 'bills')]
    private ?ES_BillGroup $billGroup = null;

    // One-To-Many
    #[ORM\OneToMany(mappedBy: 'bill', targetEntity: ES_BillItem::class)]
    private Collection $billItems;

    // Many-To-Many
    #[ORM\ManyToMany(targetEntity: ES_BillMeta::class, mappedBy: 'bills')]
    private Collection $billMetas;

    public function __construct()
    {
        $this->billItems = new ArrayCollection();
        $this->billMetas = new ArrayCollection();
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): void
    {
        $this->user = $user;
    }

    public function getBillMain(): ?ES_BillMain
    {
        return $this->billMain;
    }

    public function setBillMain(?ES_BillMain $billMain): void
    {
        $this->billMain = $billMain;
    }

    public function getBillItems(): Collection
    {
        return $this->billItems;
    }

    public function setBillItems(Collection $billItems): void
    {
        $this->billItems = $billItems;
    }

    public function getBillGroup(): ?ES_BillGroup
    {
        return $this->billGroup;
    }

    public function setBillGroup(?ES_BillGroup $billGroup): void
    {
        $this->billGroup = $billGroup;
    }

    public function getBillMetas(): Collection
    {
        return $this->billMetas;
    }

    public function setBillMetas(Collection $billMetas): void
    {
        $this->billMetas = $billMetas;
    }
}
