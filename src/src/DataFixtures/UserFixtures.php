<?php


namespace App\DataFixtures;


use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;



class UserFixtures extends BaseFixtures
{

    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;
    private const ADMINS = array(
        array(
            'email' => 'a.msouber@gmail.com',
            'password' => 'cisco',
            'firstName' => "Ahmed",
            'lastName' => "M'SOUBER"
        ),
        array(
            'email' => 'ahmed.msouber@gmail.com',
            'password' => 'cisco',
            'firstName' => "Ahmed",
            'lastName' => "M'SOUBER"
        )
    );

    /**
     * @required
     */
    public function setDependency
    (
        UserPasswordEncoderInterface $encoder
    )
    {
        $this->encoder = $encoder;
    }
    protected function loadData(ObjectManager $manager)
    {

        $oAdminUser = new User();
        $oAdminUser->setEmail($this->faker->randomElement(self::ADMINS)['email']);
        $oAdminUser->setPassword($this->encoder->encodePassword($oAdminUser, 'cisco'));
        $oAdminUser->setFirstName("Ahmed");
        $oAdminUser->setLastName("M'SOUBER");
        $oAdminUser->setRoles(['ROLE_ADMIN']);
        $oAdminUser->setStatus(User::IS_CREATED);
        $oAdminUser->setOptin(User::OPTIN);
        $this->manager->persist($oAdminUser);

        $this->createMany(User::class, 7, function (User $oUser){
            $oUser->setEmail($this->faker->safeEmail());
            $encodedPassword = $this->encoder->encodePassword($oUser, $this->faker->password);
            $oUser->setPassword($encodedPassword);
            $oUser->setFirstName($this->faker->firstName());
            $oUser->setLastName($this->faker->lastName());
            $oUser->setRoles(['ROLE_USER']);
            $oUser->setStatus(User::IS_CREATED);
            $oUser->setOptin($this->faker->boolean);
        });
        $this->manager->flush();
    }

}