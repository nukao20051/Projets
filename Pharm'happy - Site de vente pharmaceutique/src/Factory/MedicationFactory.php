<?php

namespace App\Factory;

use App\Entity\Medication;
use App\Repository\MedicationRepository;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;
use Zenstruck\Foundry\Persistence\Proxy;
use Zenstruck\Foundry\Persistence\ProxyRepositoryDecorator;

/**
 * @extends PersistentProxyObjectFactory<Medication>
 *
 * @method        Medication|Proxy                              create(array|callable $attributes = [])
 * @method static Medication|Proxy                              createOne(array $attributes = [])
 * @method static Medication|Proxy                              find(object|array|mixed $criteria)
 * @method static Medication|Proxy                              findOrCreate(array $attributes)
 * @method static Medication|Proxy                              first(string $sortedField = 'id')
 * @method static Medication|Proxy                              last(string $sortedField = 'id')
 * @method static Medication|Proxy                              random(array $attributes = [])
 * @method static Medication|Proxy                              randomOrCreate(array $attributes = [])
 * @method static MedicationRepository|ProxyRepositoryDecorator repository()
 * @method static Medication[]|Proxy[]                          all()
 * @method static Medication[]|Proxy[]                          createMany(int $number, array|callable $attributes = [])
 * @method static Medication[]|Proxy[]                          createSequence(iterable|callable $sequence)
 * @method static Medication[]|Proxy[]                          findBy(array $attributes)
 * @method static Medication[]|Proxy[]                          randomRange(int $min, int $max, array $attributes = [])
 * @method static Medication[]|Proxy[]                          randomSet(int $number, array $attributes = [])
 */
final class MedicationFactory extends PersistentProxyObjectFactory
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
        return Medication::class;
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    protected function defaults(): array|callable
    {
        $choice = rand(1, 2);
        if (1 === $choice) {
            $unit = 'mg';
        } else {
            $unit = 'ml';
        }

        return [
            'name' => self::faker()->text(100),
            'price' => self::faker()->randomFloat(2, 5, 20),
            'text' => self::faker()->text(1024),
            'dosage' => self::faker()->randomFloat(2, 500, 2000),
            'unit' => $unit,
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): static
    {
        return $this
            // ->afterInstantiate(function(Medication $medication): void {})
        ;
    }
}
