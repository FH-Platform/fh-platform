<?php

namespace FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\Setting;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\User;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\Trait\AllTypesTrait;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\Trait\IdTrait;

#[ORM\Entity]
class ES_Setting
{
    use IdTrait;
    use AllTypesTrait;

    // One-To-One (back)
    #[ORM\OneToOne(mappedBy: 'setting', targetEntity: User::class)]
    private ?User $user = null;

    // One-To-One
    #[ORM\OneToOne(mappedBy: 'setting', targetEntity: ES_SettingMain::class)]
    private ?ES_SettingMain $settingMain = null;

    // Many-To-One
    #[ORM\ManyToOne(targetEntity: ES_SettingGroup::class, inversedBy: 'settings')]
    private ?ES_SettingGroup $settingGroup = null;

    // One-To-Many
    #[ORM\OneToMany(mappedBy: 'setting', targetEntity: ES_SettingItem::class)]
    private Collection $settingItems;

    // Many-To-Many
    #[ORM\ManyToMany(targetEntity: ES_SettingMeta::class, mappedBy: 'settings')]
    private Collection $settingMetas;

    #[ORM\OneToOne(mappedBy: 'settingTestGetterEmpty', targetEntity: User::class)]
    private ?User $user2 = null;

    public function __construct()
    {
        $this->settingItems = new ArrayCollection();
        $this->settingMetas = new ArrayCollection();
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): void
    {
        $this->user = $user;
    }

    public function getSettingMain(): ?ES_SettingMain
    {
        return $this->settingMain;
    }

    public function setSettingMain(?ES_SettingMain $settingMain): void
    {
        $this->settingMain = $settingMain;
    }

    public function getSettingItems(): Collection
    {
        return $this->settingItems;
    }

    public function setSettingItems(Collection $settingItems): void
    {
        $this->settingItems = $settingItems;
    }

    public function getSettingGroup(): ?ES_SettingGroup
    {
        return $this->settingGroup;
    }

    public function setSettingGroup(?ES_SettingGroup $settingGroup): void
    {
        $this->settingGroup = $settingGroup;
    }

    public function getSettingMetas(): Collection
    {
        return $this->settingMetas;
    }

    public function setSettingMetas(Collection $settingMetas): void
    {
        $this->settingMetas = $settingMetas;
    }
}
