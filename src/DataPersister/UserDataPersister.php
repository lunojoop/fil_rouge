<?php
namespace App\DataPersister;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserDataPersister implements ContextAwareDataPersisterInterface 
{
    private $entityManager;
    private $userPasswordEncoder;
    public function __construct(EntityManagerInterface $entityManager, UserPasswordEncoderInterface $userPasswordEncoder)
    {
        $this->entityManager = $entityManager;
        $this->userPasswordEncoder = $userPasswordEncoder;
    }
    public function supports($data, array $context = []): bool
    {
        return $data instanceof User;
    }
    public function persist($data, array $context = [])
    {
        
        $data->setPassword(
            $this->userPasswordEncoder->encodePassword($data, $data->getPlainPassword())
        );
        $data->eraseCredentials();
        $this->entityManager->persist($data);
        $this->entityManager->flush();
    }
    public function remove($data, array $context = [])
    {
        $this->entityManager->remove($data);
        $this->entityManager->flush();
    }
}

?>