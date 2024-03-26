<?php

namespace FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\Bill;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\Trait\AllTypesTrait;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\Trait\IdTrait;

#[ORM\Entity]
class BillItemLine
{
    use IdTrait;
    use AllTypesTrait;

    #[ORM\ManyToOne(targetEntity: BillItem::class, inversedBy: 'billItemLines')]
    private ?BillItem $billItem;

    #[ORM\ManyToMany(targetEntity: BillItemLineMeta::class, mappedBy: 'billItemLines')]
    private Collection $billItemLineMetas;

    public function __construct()
    {
        $this->billItemLineMetas = new ArrayCollection();
    }

    public function getBillItem(): ?BillItem
    {
        return $this->billItem;
    }

    public function setBillItem(?BillItem $billItem): void
    {
        $this->billItem = $billItem;
    }

    public function getBillItemLineMetas(): Collection
    {
        return $this->billItemLineMetas;
    }

    public function setBillItemLineMetas(Collection $billItemLineMetas): void
    {
        $this->billItemLineMetas = $billItemLineMetas;
    }
}
