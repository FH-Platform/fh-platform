<?php

namespace FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\Bill;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\Trait\AllTypesTrait;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\Trait\IdTrait;

#[ORM\Entity]
class BillItem
{
    use IdTrait;
    use AllTypesTrait;

    #[ORM\ManyToOne(targetEntity: Bill::class, inversedBy: 'billItems')]
    private ?Bill $bill = null;

    #[ORM\OneToMany(mappedBy: 'billItem', targetEntity: BillItemLine::class)]
    private Collection $billItemLines;

    public function getBill(): ?Bill
    {
        return $this->bill;
    }

    public function setBill(?Bill $bill): void
    {
        $this->bill = $bill;
    }

    public function getBillItemLines(): Collection
    {
        return $this->billItemLines;
    }

    public function setBillItemLines(Collection $billItemLines): void
    {
        $this->billItemLines = $billItemLines;
    }
}
