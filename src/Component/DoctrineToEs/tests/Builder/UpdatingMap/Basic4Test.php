<?php

namespace FHPlatform\Component\DoctrineToEs\Tests\Builder\UpdatingMap;

use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\Bill\Bill;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\Bill\BillItem;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\Bill\BillItemLine;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\Bill\BillItemLineMeta;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\User;

class Basic4Test extends TestCaseUpdatingMap
{
    public function testSomething(): void
    {
        $classNames = [
            User::class => [
                'bills' => [
                    'testBigint',
                    'billItems' => [
                        'testDate',
                        'billItemLines' => [
                            'testDatetime',
                            'billItemLineMetas' => [
                                'testInteger',
                                'testBoolean',
                            ],
                        ],
                    ],
                ],
            ],
        ];

        $this->assertEquals([
            Bill::class => [
                User::class => [
                    'relations' => 'user',
                    'changed_fields' => [
                        'testBigint',
                    ],
                ],
            ],
            BillItem::class => [
                User::class => [
                    'relations' => 'bill.user',
                    'changed_fields' => [
                        'testDate',
                    ],
                ],
            ],
            BillItemLine::class => [
                User::class => [
                    'relations' => 'billItem.bill.user',
                    'changed_fields' => [
                        'testDatetime',
                    ],
                ],
            ],
            BillItemLineMeta::class => [
                User::class => [
                    'relations' => 'billItemLines.billItem.bill.user',
                    'changed_fields' => [
                        'testBoolean',
                        'testInteger',
                    ],
                ],
            ],
        ], $this->updatingMapBuilder->build($classNames));
    }
}
