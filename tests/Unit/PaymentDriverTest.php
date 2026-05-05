<?php

namespace Tests\Unit;

use App\DTOs\PaymentResult;
use App\Enums\PaymentStatus;
use App\Models\Order;
use App\Payments\Drivers\BankTransferDriver;
use App\Payments\Drivers\CardDriver;
use App\Payments\Drivers\MobileMoneyDriver;
use Tests\TestCase;

class PaymentDriverTest extends TestCase
{
    private function fakeOrder(int $amount = 5000): Order
    {
        return tap(new Order(), function (Order $o) use ($amount) {
            $o->reference   = 'CMD-TEST-XYZ';
            $o->total_cents = $amount;
            $o->currency    = 'XOF';
        });
    }

    public function test_card_driver_returns_redirect_result(): void
    {
        $driver = new CardDriver(['provider' => 'stripe', 'label' => 'Carte']);
        $result = $driver->initiate($this->fakeOrder());

        $this->assertTrue($result->shouldRedirect());
        $this->assertNotNull($result->providerReference);
    }

    public function test_mobile_money_driver_validates_phone(): void
    {
        $driver = new MobileMoneyDriver([
            'operators' => ['orange' => ['label' => 'Orange Money']],
        ]);

        $result = $driver->initiate($this->fakeOrder(), ['operator' => 'orange', 'phone' => 'invalid']);
        $this->assertSame(PaymentStatus::FAILED, $result->status);
    }

    public function test_mobile_money_driver_returns_pending_for_valid_payload(): void
    {
        $driver = new MobileMoneyDriver([
            'operators' => ['orange' => ['label' => 'Orange Money']],
        ]);

        $result = $driver->initiate($this->fakeOrder(), [
            'operator' => 'orange',
            'phone'    => '+2250711223344',
        ]);
        $this->assertTrue($result->isPending());
    }

    public function test_bank_transfer_driver_returns_pending_with_bank_data(): void
    {
        $driver = new BankTransferDriver([
            'bank_name'   => 'Ecobank',
            'iban'        => 'CI05 0001 2345 6789',
            'beneficiary' => 'EbookSaaS',
        ]);
        $result = $driver->initiate($this->fakeOrder());
        $this->assertTrue($result->isPending());
        $this->assertSame('Ecobank', $result->rawResponse['bank_name']);
    }

    public function test_payment_result_factories_are_consistent(): void
    {
        $this->assertSame(PaymentStatus::PAID,   PaymentResult::success()->status);
        $this->assertSame(PaymentStatus::FAILED, PaymentResult::failed('nope')->status);
        $this->assertSame(PaymentStatus::PROCESSING, PaymentResult::redirect('https://x.test')->status);
    }
}
