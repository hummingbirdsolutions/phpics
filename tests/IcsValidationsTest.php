<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

use HummingbirdSolutions\Phpics\Index;

final class IcsValidationsTest extends TestCase
{
    public function testCanBeCreatedFromValidEmailAddress(): void
    {
        $a = new Index();

        $this->expectException(Exception::class);
        $a->generateIcs();
    }
}
