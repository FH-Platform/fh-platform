<?php

namespace FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\Bill;

use Doctrine\ORM\Mapping as ORM;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\Trait\AllTypesTrait;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\Trait\IdTrait;

#[ORM\Entity]
class BillMain
{
    use IdTrait;
    use AllTypesTrait;

    #[ORM\OneToOne(inversedBy: 'billMain', targetEntity: Bill::class)]
    private ?Bill $bill = null;

    public function getBill(): ?Bill
    {
        return $this->bill;
    }

    public function setBill(?Bill $bill): void
    {
        $this->bill = $bill;
    }
}
