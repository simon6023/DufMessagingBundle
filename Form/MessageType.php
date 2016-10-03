<?php

namespace Duf\MessagingBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;

use Duf\AdminBundle\Form\Type\DufAdminChoiceType;
use Duf\AdminBundle\Form\Type\DufAdminTextareaType;
use Duf\AdminBundle\Form\Type\DufAdminTextType;

class MessageType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if (isset($options['users']) && null !== $options['users']) {
            $builder->add('users', DufAdminChoiceType::class, array(
                    'mapped'    => false,
                    'label'     => 'Users',
                    'required'  => true,
                    'multiple'  => true,
                    'choices'   => $options['users'],
                    'data'      => array_keys($options['users']),
                )
            );
        }

        $builder
            ->add('subject', DufAdminTextType::class, array(
                    'mapped'        => false,
                    'required'      => true,
                    'attr'          => array(
                            'placeholder' => 'Subject',
                        ),
                )
            )
            ->add('content', DufAdminTextareaType::class, array())
            ->add('submit', SubmitType::class, array('label' => 'Send'))
            ->add('save', ButtonType::class, array(
                    'label'     => 'Save',
                    'attr'      => array(
                        'class'     => 'btn btn-default duf-messaging-save-draft',
                    ),
                )
            )
        ;
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class'    => 'Duf\MessagingBundle\Entity\Message',
            'users'         => null,
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'duf_messagingbundle_message';
    }
}
