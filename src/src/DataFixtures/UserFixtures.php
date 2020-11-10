<?php


namespace App\DataFixtures;


use App\Core\Services\UploadFileHelper;
use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;



class UserFixtures extends BaseFixtures
{

    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;
    /**
     * @var UploadFileHelper $uploadFileHelper
     */
    private  $uploadFileHelper;

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

    private static $articleImages =[
        'braunaspirator-5fa2eaf9638b7.jpeg',
        'comezy-5fa1931a5799a.jpeg',
        'yoocca4-5fa58cb07d04d.jpeg'
    ];

    /**
     * @required
     * @param UserPasswordEncoderInterface $encoder
     * @param UploadFileHelper $uploadFileHelper
     */
    public function setDependency
    (
        UserPasswordEncoderInterface $encoder,
        UploadFileHelper $uploadFileHelper
    )
    {
        $this->encoder = $encoder;
        $this->uploadFileHelper = $uploadFileHelper;
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
            $imageFilename = $this->fakeUploadImage();
            $oUser->setImage($imageFilename);
        });
        $this->manager->flush();
    }
    private function fakeUploadImage(): string
    {
        $randomImage = $this->faker->randomElement(self::$articleImages);
        $fs = new Filesystem();
        $targetPath = sys_get_temp_dir().'/'.$randomImage;
        $fs->copy(__DIR__.'/images/'.$randomImage, $targetPath, true);
        return $this->uploadFileHelper
            ->upload(new File($targetPath),null);
    }

}