<?php

namespace App\Tests\Unit;

use App\Domain\VatNumber\Service\VatNumberValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class VatNumberValidatorTest extends KernelTestCase
{
    /**
     * @throws \Exception
     */
    public function testValidateResult(): void {
        self::bootKernel();
        $container = static::getContainer();

        /**
         * @var VatNumberValidatorInterface $vatNumberValidator
         */
        $vatNumberValidator = $container->get(VatNumberValidatorInterface::class);

        $this->assertFalse($vatNumberValidator->validate(''));
        $this->assertFalse($vatNumberValidator->validate('   '));
        $this->assertFalse($vatNumberValidator->validate('DE12345678'));
        $this->assertFalse($vatNumberValidator->validate('DE1234567890'));
        $this->assertFalse($vatNumberValidator->validate('DE12345678A'));
        $this->assertFalse($vatNumberValidator->validate('XX123456789'));
        $this->assertFalse($vatNumberValidator->validate('IT1234567890'));
        $this->assertFalse($vatNumberValidator->validate('IT123456789012'));
        $this->assertFalse($vatNumberValidator->validate('IT123456A7890'));
        $this->assertFalse($vatNumberValidator->validate('GR12345678'));
        $this->assertFalse($vatNumberValidator->validate('GR1234567890'));
        $this->assertFalse($vatNumberValidator->validate('GR12345678A'));
        $this->assertFalse($vatNumberValidator->validate('FRAB12345678'));
        $this->assertFalse($vatNumberValidator->validate('FRZQ1234567890'));
        $this->assertFalse($vatNumberValidator->validate('FRXX12345678A'));
        $this->assertFalse($vatNumberValidator->validate('FRA1123456789'));

        $this->assertEquals('DE', $vatNumberValidator->validate('DE123456789'));
        $this->assertEquals('IT', $vatNumberValidator->validate('IT12345678901'));
        $this->assertEquals('GR', $vatNumberValidator->validate('GR123456789'));
        $this->assertEquals('FR', $vatNumberValidator->validate('FRXX123456789'));
    }
}