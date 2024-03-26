<?php

namespace FHPlatform\Component\DoctrineToEs\Tests\Util\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    public ?int $id = null;

    #[ORM\Column(type: 'string', nullable: true)]
    private ?bool $testBoolean;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $testInteger;

    #[ORM\Column(type: 'bigint', nullable: true)]
    private ?int $testBigint;

    #[ORM\Column(type: 'smallint', nullable: true)]
    private ?int $testSmallint;

    #[ORM\Column(type: 'float', nullable: true)]
    private ?float $testFloat;

    #[ORM\Column(type: 'decimal', nullable: true)]
    private ?float $testDecimal;

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $testString;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $testText;

    #[ORM\Column(type: 'date', nullable: true)]
    private ?\DateTimeInterface $testDate;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTimeInterface $testDatetime;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTestBoolean(): ?bool
    {
        return $this->testBoolean;
    }

    public function setTestBoolean(?bool $testBoolean): void
    {
        $this->testBoolean = $testBoolean;
    }

    public function getTestInteger(): ?int
    {
        return $this->testInteger;
    }

    public function setTestInteger(?int $testInteger): void
    {
        $this->testInteger = $testInteger;
    }

    public function getTestBigint(): ?int
    {
        return $this->testBigint;
    }

    public function setTestBigint(?int $testBigint): void
    {
        $this->testBigint = $testBigint;
    }

    public function getTestSmallint(): ?int
    {
        return $this->testSmallint;
    }

    public function setTestSmallint(?int $testSmallint): void
    {
        $this->testSmallint = $testSmallint;
    }

    public function getTestFloat(): ?float
    {
        return $this->testFloat;
    }

    public function setTestFloat(?float $testFloat): void
    {
        $this->testFloat = $testFloat;
    }

    public function getTestDecimal(): ?float
    {
        return $this->testDecimal;
    }

    public function setTestDecimal(?float $testDecimal): void
    {
        $this->testDecimal = $testDecimal;
    }

    public function getTestString(): ?string
    {
        return $this->testString;
    }

    public function setTestString(?string $testString): void
    {
        $this->testString = $testString;
    }

    public function getTestText(): ?string
    {
        return $this->testText;
    }

    public function setTestText(?string $testText): void
    {
        $this->testText = $testText;
    }

    public function getTestDate(): ?\DateTimeInterface
    {
        return $this->testDate;
    }

    public function setTestDate(?\DateTimeInterface $testDate): void
    {
        $this->testDate = $testDate;
    }

    public function getTestDatetime(): ?\DateTimeInterface
    {
        return $this->testDatetime;
    }

    public function setTestDatetime(?\DateTimeInterface $testDatetime): void
    {
        $this->testDatetime = $testDatetime;
    }
}
