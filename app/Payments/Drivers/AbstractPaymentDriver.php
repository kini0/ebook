<?php

namespace App\Payments\Drivers;

use App\Payments\Contracts\PaymentGatewayInterface;

abstract class AbstractPaymentDriver implements PaymentGatewayInterface
{
    /** @var array<string, mixed> */
    protected array $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function label(): string
    {
        return (string) ($this->config['label'] ?? $this->key());
    }

    /**
     * Generate a deterministic but unique provider-bound reference.
     */
    protected function buildProviderReference(string $orderRef): string
    {
        return sprintf('%s-%s-%s',
            strtoupper($this->key()),
            $orderRef,
            substr(bin2hex(random_bytes(4)), 0, 8)
        );
    }
}
