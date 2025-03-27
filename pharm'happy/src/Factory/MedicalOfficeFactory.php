<?php

namespace App\Factory;

use App\Entity\MedicalOffice;
use App\Repository\MedicalOfficeRepository;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;
use Zenstruck\Foundry\Persistence\Proxy;
use Zenstruck\Foundry\Persistence\ProxyRepositoryDecorator;

/**
 * @extends PersistentProxyObjectFactory<MedicalOffice>
 *
 * @method        MedicalOffice|Proxy                              create(array|callable $attributes = [])
 * @method static MedicalOffice|Proxy                              createOne(array $attributes = [])
 * @method static MedicalOffice|Proxy                              find(object|array|mixed $criteria)
 * @method static MedicalOffice|Proxy                              findOrCreate(array $attributes)
 * @method static MedicalOffice|Proxy                              first(string $sortedField = 'id')
 * @method static MedicalOffice|Proxy                              last(string $sortedField = 'id')
 * @method static MedicalOffice|Proxy                              random(array $attributes = [])
 * @method static MedicalOffice|Proxy                              randomOrCreate(array $attributes = [])
 * @method static MedicalOfficeRepository|ProxyRepositoryDecorator repository()
 * @method static MedicalOffice[]|Proxy[]                          all()
 * @method static MedicalOffice[]|Proxy[]                          createMany(int $number, array|callable $attributes = [])
 * @method static MedicalOffice[]|Proxy[]                          createSequence(iterable|callable $sequence)
 * @method static MedicalOffice[]|Proxy[]                          findBy(array $attributes)
 * @method static MedicalOffice[]|Proxy[]                          randomRange(int $min, int $max, array $attributes = [])
 * @method static MedicalOffice[]|Proxy[]                          randomSet(int $number, array $attributes = [])
 */
final class MedicalOfficeFactory extends PersistentProxyObjectFactory
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
        return MedicalOffice::class;
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    protected function defaults(): array|callable
    {
        $person = PersonFactory::new()->create();
        if (0 === AddressFactory::repository()->count(['person' => $person->_real()])) {
            AddressFactory::createOne(['person' => $person->_real()]);
        }
        $addresses = AddressFactory::repository()->findBy(['person' => $person->_real()]);
        $address = $addresses[0];
        $location = $address->getNum().' '.$address->getStreet().', '.$address->getCity().' ('.$address->getPc().')';

        return [
            'name' => 'Pharmacie '.mb_convert_case(self::faker()->word(), MB_CASE_TITLE),
            'phone' => self::faker()->phoneNumber(),
            'location' => $location,
            'persons' => [$person],
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): static
    {
        return $this->afterInstantiate(function (MedicalOffice $medicalOffice): void {
            $persons = $medicalOffice->getPersons();
            foreach ($persons as $person) {
                $person->setMedicalOffice($medicalOffice);
                $person->setRoles(['ROLE_PHARMACY']);
            }
        });
    }
}
