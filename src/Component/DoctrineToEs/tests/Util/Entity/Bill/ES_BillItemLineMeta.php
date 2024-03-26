<?php

namespace FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\Bill;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\Trait\AllTypesTrait;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\Trait\IdTrait;

#[ORM\Entity]
class ES_BillItemLineMeta
{
    use IdTrait;
    use AllTypesTrait;

    #[ORM\ManyToMany(targetEntity: ES_BillItemLine::class, inversedBy: 'billItemLineMetas')]
    private Collection $billItemLines;

    public function __construct()
    {
        $this->billItemLines = new ArrayCollection();
    }

    public function getBillItemLines(): Collection
    {
        return $this->billItemLines;
    }

    public function setBillItemLines(Collection $billItemLines): void
    {
        $this->billItemLines = $billItemLines;
    }

    public function addBillItemLine(ES_BillItemLine $billItemLine): self
    {
        $this->billItemLines->add($billItemLine);

        return $this;
    }
}
