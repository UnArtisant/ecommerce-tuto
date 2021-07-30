<?php

namespace App\Controller\Admin;

use App\Entity\Products;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Vich\UploaderBundle\Form\Type\VichImageType;

class ProductsCrudController extends AbstractCrudController
{


    public static function getEntityFqcn(): string
    {
        return Products::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud->setEntityLabelInSingular("Product")
            ->setSearchFields(['name', 'price', 'status'])
            ->setDefaultSort(['createdAt' => 'DESC'])
            ->setPaginatorFetchJoinCollection(false)
            ->setEntityLabelInPlural("Products");
    }

    public function createEntity(string $entityFqcn)
    {
        $product = new Products();
        $product->setCreatedBy($this->getUser())
            ->setUpdatedBy($this->getUser());

        return $product;
    }

    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $entityInstance->setUpdatedBy($this->getUser());
        $entityManager->flush();
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name'),
            IntegerField::new('stock'),
            MoneyField::new("price")->setCurrency('EUR'),
            ChoiceField::new("status")
                ->setChoices(["display"=> Products::PRODUCT_VISIBLE, "hidden" => Products::PRODUCT_HIDDEN]),
            ImageField::new("image")
                ->onlyOnIndex()
            ->setBasePath("/uploads/products/")
            ,
            TextareaField::new('description'),
            TextField::new("imageFile")
                ->setFormType(VichImageType::class)
            ->onlyWhenCreating()
        ];
    }

}
