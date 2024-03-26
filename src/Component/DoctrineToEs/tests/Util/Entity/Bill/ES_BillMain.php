<?php

namespace FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\Bill;

use Doctrine\ORM\Mapping as ORM;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\Trait\AllTypesTrait;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\Trait\IdTrait;

#[ORM\Entity]
class ES_BillMain
{
    use IdTrait;
    use AllTypesTrait;

    #[ORM\OneToOne(inversedBy: 'billMain', targetEntity: ES_Bill::class)]
    private ?ES_Bill $bill = null;

    public function getBill(): ?ES_Bill
    {
        return $this->bill;
    }

    public function setBill(?ES_Bill $bill): void
    {
        $this->bill = $bill;
    }
}
