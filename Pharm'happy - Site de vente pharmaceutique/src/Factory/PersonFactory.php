<?php

namespace App\Factory;

use App\Entity\Person;
use App\Repository\PersonRepository;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;
use Zenstruck\Foundry\Persistence\Proxy;
use Zenstruck\Foundry\Persistence\ProxyRepositoryDecorator;

/**
 * @extends PersistentProxyObjectFactory<Person>
 *
 * @method        Person|Proxy                              create(array|callable $attributes = [])
 * @method static Person|Proxy                              createOne(array $attributes = [])
 * @method static Person|Proxy                              find(object|array|mixed $criteria)
 * @method static Person|Proxy                              findOrCreate(array $attributes)
 * @method static Person|Proxy                              first(string $sortedField = 'id')
 * @method static Person|Proxy                              last(string $sortedField = 'id')
 * @method static Person|Proxy                              random(array $attributes = [])
 * @method static Person|Proxy                              randomOrCreate(array $attributes = [])
 * @method static PersonRepository|ProxyRepositoryDecorator repository()
 * @method static Person[]|Proxy[]                          all()
 * @method static Person[]|Proxy[]                          createMany(int $number, array|callable $attributes = [])
 * @method static Person[]|Proxy[]                          createSequence(iterable|callable $sequence)
 * @method static Person[]|Proxy[]                          findBy(array $attributes)
 * @method static Person[]|Proxy[]                          randomRange(int $min, int $max, array $attributes = [])
 * @method static Person[]|Proxy[]                          randomSet(int $number, array $attributes = [])
 */
final class PersonFactory extends PersistentProxyObjectFactory
{
    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services
     *
     * @todo inject services if required
     */
    private \Transliterator $transliterator;
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        parent::__construct();
        $this->transliterator = \Transliterator::create('Any-Lower; Latin-ASCII');
        $this->passwordHasher = $passwordHasher;
    }

    public static function class(): string
    {
        return Person::class;
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    protected function defaults(): array|callable
    {
        $lastName = self::faker()->lastName();
        $firstName = self::faker()->firstName();
        $birthDat = self::faker()->date();
        $phone = self::faker()->phoneNumber();

        return [
            'firstname' => $firstName,
            'lastname' => $lastName,
            'birthDat' => new \DateTime($birthDat),
            'phone' => $phone,
            'email' => $this->normalizeName($firstName)
                .'.'
                .$this->normalizeName($lastName)
                .'@'
                .self::faker()->safeEmailDomain(),
            'password' => 'test',
        ];
    }

    public function normalizeName(string $name): string
    {
        return $this->transliterator->transliterate(preg_replace('/ /', '-', $name));
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): static
    {
        return $this
            ->afterInstantiate(function (Person $person) {
                $person->setPassword($this->passwordHasher->hashPassword($person, $person->getPassword()));
            })
        ;
    }
}
