<?php

namespace FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\Bill;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\Trait\AllTypesTrait;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\Trait\IdTrait;

#[ORM\Entity]
class BillMeta
{
    use IdTrait;
    use AllTypesTrait;

    #[ORM\ManyToMany(targetEntity: Bill::class, inversedBy: 'billMetas')]
    private Collection $bills;

    public function __construct()
    {
        $this->bills = new ArrayCollection();
    }

    public function getBills(): Collection
    {
        return $this->bills;
    }

    public function setBills(Collection $bills): void
    {
        $this->bills = $bills;
    }

    public function addBill(Bill $bill): self
    {
        $this->bills->add($bill);

        return $this;
    }
}
