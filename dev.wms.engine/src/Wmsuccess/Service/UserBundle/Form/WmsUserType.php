<?php

namespace App\Wmsuccess\Service\UserBundle\Form;

use App\Wmsuccess\Service\MetierManagerBundle\Utils\RoleName;
use App\Wmsuccess\Service\UserBundle\Entity\WmsUser;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class WmsUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if (!$options['is_edit']) {
            $builder->add('wmsRole', EntityType::class, array(
                'label'         => 'Rôle',
                'class'         => 'App\Wmsuccess\Service\MetierManagerBundle\Entity\WmsRole',
                'query_builder' => function (EntityRepository $_er) {
                    $_query_builder = $_er->createQueryBuilder('rl');
                    $_query_builder->orderBy('rl.rlName', 'ASC');

                    return $_query_builder;
                },
                'choice_label'  => 'rlName',
                'multiple'      => false,
                'expanded'      => false,
                'required'      => true
            ));
        }

        $builder
            ->add('email', EmailType::class, array(
                'label' => 'E-mail',
                'required' => true
            ))
            ->add('usrFirstname', TextType::class, array(
                'label' => 'Prénom(s)',
                'required' => true
            ))
            ->add('usrLastname', TextType::class, array(
                'label' => 'Nom',
                'required' => true
            ))
            ->add('password', RepeatedType::class, [
                'type'           => PasswordType::class,
                'first_options'  => ['label' => 'Mot de passe'],
                'second_options' => ['label' => 'Confirmation mot de passe'],
                'required'       => $options['is_edit'] ? false : true
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => WmsUser::class,
            'is_edit'    => false,
            'is_profile' => false
        ]);
    }
}