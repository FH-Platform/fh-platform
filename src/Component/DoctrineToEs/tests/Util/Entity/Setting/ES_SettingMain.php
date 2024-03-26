<?php

namespace FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\Setting;

use Doctrine\ORM\Mapping as ORM;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\Trait\AllTypesTrait;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\Trait\IdTrait;

#[ORM\Entity]
class ES_SettingMain
{
    use IdTrait;
    use AllTypesTrait;

    #[ORM\OneToOne(inversedBy: 'settingMain', targetEntity: ES_Setting::class)]
    private ?ES_Setting $setting = null;

    public function getSetting(): ?ES_Setting
    {
        return $this->setting;
    }

    public function setSetting(?ES_Setting $setting): void
    {
        $this->setting = $setting;
    }
}
