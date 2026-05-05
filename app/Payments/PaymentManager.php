<?php

namespace App\Payments;

use App\Exceptions\PaymentException;
use App\Payments\Contracts\PaymentGatewayInterface;
use Illuminate\Contracts\Container\Container;
use Illuminate\Support\Collection;

/**
 * Resolves payment gateways at runtime from config/payment.php.
 *
 * Acts as a Manager (Laravel-style) — but instead of magic forwarding,
 * we expose explicit gateway() / available() methods for type-safety.
 */
class PaymentManager
{
    /** @var array<string, PaymentGatewayInterface> */
    protected array $resolved = [];

    public function __construct(
        protected Container $container,
        protected array $config,
    ) {}

    /**
     * Default gateway key (from config).
     */
    public function defaultKey(): string
    {
        return (string) ($this->config['default'] ?? 'card');
    }

    /**
     * Resolve a gateway driver by key.
     *
     * @throws PaymentException
     */
    public function gateway(?string $key = null): PaymentGatewayInterface
    {
        $key ??= $this->defaultKey();

        if (isset($this->resolved[$key])) {
            return $this->resolved[$key];
        }

        $driverConfig = $this->config['drivers'][$key] ?? null;
        if (! $driverConfig) {
            throw new PaymentException("Méthode de paiement « {$key} » introuvable.");
        }
        if (empty($driverConfig['enabled'])) {
            throw new PaymentException("Méthode de paiement « {$key} » désactivée.");
        }

        $class = $driverConfig['driver'] ?? null;
        if (! $class || ! class_exists($class)) {
            throw new PaymentException("Driver de paiement invalide pour « {$key} ».");
        }

        return $this->resolved[$key] = $this->container->make($class, [
            'config' => $driverConfig,
        ]);
    }

    /**
     * @return Collection<string, array{key:string,label:string,icon:string|null,enabled:bool,extra:array}>
     */
    public function available(): Collection
    {
        return collect($this->config['drivers'] ?? [])
            ->filter(fn ($cfg) => ! empty($cfg['enabled']))
            ->map(fn ($cfg, $key) => [
                'key'     => $key,
                'label'   => $cfg['label'] ?? $key,
                'icon'    => $cfg['icon'] ?? null,
                'enabled' => (bool) ($cfg['enabled'] ?? false),
                'extra'   => $cfg,
            ]);
    }
}
