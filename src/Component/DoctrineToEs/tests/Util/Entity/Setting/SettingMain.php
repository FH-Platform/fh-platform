<?php

namespace FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\Setting;

use Doctrine\ORM\Mapping as ORM;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\Trait\AllTypesTrait;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\Trait\IdTrait;

#[ORM\Entity]
class SettingMain
{
    use IdTrait;
    use AllTypesTrait;

    #[ORM\OneToOne(inversedBy: 'settingMain', targetEntity: Setting::class)]
    private ?Setting $setting = null;

    public function getSetting(): ?Setting
    {
        return $this->setting;
    }

    public function setSetting(?Setting $setting): void
    {
        $this->setting = $setting;
    }
}
