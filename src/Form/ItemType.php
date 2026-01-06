<?php

namespace App\Form;

use App\Entity\Cabinet;
use App\Entity\Drawer;
use App\Entity\Item;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfonycasts\DynamicForms\DynamicFormBuilder;
use Symfonycasts\DynamicForms\DependentField;

class ItemType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder = new DynamicFormBuilder($builder);

        $builder
            ->add('name', TextType::class, [
                'label' => 'Item Name',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('quantity', IntegerType::class, [
                'label' => 'Quantity',
                'attr' => ['class' => 'form-control', 'min' => 0],
            ])
            ->add('cabinet', EntityType::class, [
                'class' => Cabinet::class,
                'choice_label' => 'name',
                'placeholder' => 'Select a cabinet',
                'mapped' => false, // filter-only field
                'required' => false,
                'attr' => ['class' => 'form-select'],
            ])
        ;

        // Drawer depends on Cabinet
        $builder->addDependent('drawer', 'cabinet', function (
            DependentField $field,
            ?Cabinet $cabinet
        ) {
            $field->add(EntityType::class, [
                'class' => Drawer::class,
                'choice_label' => 'label',
                'placeholder' => $cabinet ? 'Select a drawer' : 'Select a cabinet first',
                'attr' => ['class' => 'form-select'],
                'query_builder' => function (EntityRepository $er) use ($cabinet) {
                    $qb = $er->createQueryBuilder('d')
                        ->orderBy('d.label', 'ASC');

                    if ($cabinet) {
                        // Assumes Drawer has ManyToOne: drawer.cabinet
                        $qb->andWhere('d.cabinet = :cabinet')
                            ->setParameter('cabinet', $cabinet);
                    } else {
                        // no cabinet selected -> show none
                        $qb->andWhere('1 = 0');
                    }

                    return $qb;
                },
            ]);
        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Item::class,
        ]);
    }
}
