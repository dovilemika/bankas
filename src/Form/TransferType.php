<?php

namespace App\Form;

use App\Entity\Account;
use App\Entity\Transfer;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TransferType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $sender = $builder->getData()->getSender();

        $builder
            ->add('purpose')
            ->add('amount')
            ->add('receiver', EntityType::class, [
                'class' => Account::class,
                'query_builder' => function (EntityRepository $er) use ($sender) {
                return $er->createQueryBuilder('u')
                    ->where('u.id != :uid')
                    ->setParameter('uid', $sender->getId());
                }
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Transfer::class,
        ]);
    }
}
