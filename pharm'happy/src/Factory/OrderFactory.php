<?php

namespace App\Factory;

use App\Entity\Order;
use App\Repository\OrderRepository;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;
use Zenstruck\Foundry\Persistence\Proxy;
use Zenstruck\Foundry\Persistence\ProxyRepositoryDecorator;

/**
 * @extends PersistentProxyObjectFactory<Order>
 *
 * @method        Order|Proxy                              create(array|callable $attributes = [])
 * @method static Order|Proxy                              createOne(array $attributes = [])
 * @method static Order|Proxy                              find(object|array|mixed $criteria)
 * @method static Order|Proxy                              findOrCreate(array $attributes)
 * @method static Order|Proxy                              first(string $sortedField = 'id')
 * @method static Order|Proxy                              last(string $sortedField = 'id')
 * @method static Order|Proxy                              random(array $attributes = [])
 * @method static Order|Proxy                              randomOrCreate(array $attributes = [])
 * @method static OrderRepository|ProxyRepositoryDecorator repository()
 * @method static Order[]|Proxy[]                          all()
 * @method static Order[]|Proxy[]                          createMany(int $number, array|callable $attributes = [])
 * @method static Order[]|Proxy[]                          createSequence(iterable|callable $sequence)
 * @method static Order[]|Proxy[]                          findBy(array $attributes)
 * @method static Order[]|Proxy[]                          randomRange(int $min, int $max, array $attributes = [])
 * @method static Order[]|Proxy[]                          randomSet(int $number, array $attributes = [])
 */
final class OrderFactory extends PersistentProxyObjectFactory
{
    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services
     *
     * @todo inject services if required
     */
    public function __construct()
    {
    }

    public static function class(): string
    {
        return Order::class;
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    protected function defaults(): array|callable
    {
        $sample = SampleFactory::new()->create();

        return [
            'address' => AddressFactory::new(),
            'orderState' => self::faker()->randomElement(['En préparation', 'En cours de livraison', 'Livré']),
            'person' => PersonFactory::new(),
            'delivery_date' => self::faker()->dateTimeBetween('-1 years', 'now'),
            'total_price' => $sample->getMedication()->getPrice(),
            'payement' => self::faker()->randomElement(['Visa', 'Mastercard', 'Paypal', 'Revolut', 'Paysafecard']),
            'samples' => [$sample],
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): static
    {
        return $this
            // ->afterInstantiate(function(Order $order): void {})
        ;
    }
}
