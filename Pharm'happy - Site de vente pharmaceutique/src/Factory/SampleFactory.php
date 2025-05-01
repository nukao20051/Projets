<?php

namespace App\Factory;

use App\Entity\Sample;
use App\Repository\SampleRepository;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;
use Zenstruck\Foundry\Persistence\Proxy;
use Zenstruck\Foundry\Persistence\ProxyRepositoryDecorator;

/**
 * @extends PersistentProxyObjectFactory<Sample>
 *
 * @method        Sample|Proxy                              create(array|callable $attributes = [])
 * @method static Sample|Proxy                              createOne(array $attributes = [])
 * @method static Sample|Proxy                              find(object|array|mixed $criteria)
 * @method static Sample|Proxy                              findOrCreate(array $attributes)
 * @method static Sample|Proxy                              first(string $sortedField = 'id')
 * @method static Sample|Proxy                              last(string $sortedField = 'id')
 * @method static Sample|Proxy                              random(array $attributes = [])
 * @method static Sample|Proxy                              randomOrCreate(array $attributes = [])
 * @method static SampleRepository|ProxyRepositoryDecorator repository()
 * @method static Sample[]|Proxy[]                          all()
 * @method static Sample[]|Proxy[]                          createMany(int $number, array|callable $attributes = [])
 * @method static Sample[]|Proxy[]                          createSequence(iterable|callable $sequence)
 * @method static Sample[]|Proxy[]                          findBy(array $attributes)
 * @method static Sample[]|Proxy[]                          randomRange(int $min, int $max, array $attributes = [])
 * @method static Sample[]|Proxy[]                          randomSet(int $number, array $attributes = [])
 */
final class SampleFactory extends PersistentProxyObjectFactory
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
        return Sample::class;
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    protected function defaults(): array|callable
    {
        $expiration = self::faker()->dateTimeBetween('-3 month', '+2 years');
        $medication = MedicationFactory::random();
        $order = null;

        return [
            'expiration' => $expiration,
            'medication' => $medication,
            'order' => $order,
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): static
    {
        return $this
            // ->afterInstantiate(function(Sample $sample): void {})
        ;
    }
}
