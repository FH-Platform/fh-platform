<?php

namespace FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\Setting;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\Trait\AllTypesTrait;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\Trait\IdTrait;

#[ORM\Entity]
class SettingGroup
{
    use IdTrait;
    use AllTypesTrait;

    #[ORM\OneToMany(mappedBy: 'settingGroup', targetEntity: Setting::class)]
    private Collection $settings;

    public function __construct()
    {
        $this->settings = new ArrayCollection();
    }

    public function getSettings(): Collection
    {
        return $this->settings;
    }

    public function setSettings(Collection $settings): void
    {
        $this->settings = $settings;
    }
}
