<?php

namespace App\Controller\Admin;

use App\Entity\Article;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Filter\EntityFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\DateTimeFilter;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;



class ArticleCrudController extends AbstractCrudController
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }
    public static function getEntityFqcn(): string
    {
        return Article::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            yield  FormField::addTab('Article detail'),
            yield  IdField::new('id')->hideOnForm(),
            yield  DateField::new('date')->hideOnIndex(),
            yield  TextField::new('title'),
            yield  SlugField::new('slug')->setTargetFieldName('title'),
            yield  TextEditorField::new('body')->hideOnIndex(),
            yield  FormField::addTab('Publication'),
            yield  AssociationField::new('category')->setColumns(6),
            yield FormField::addRow(),
            yield  ChoiceField::new('status')->setColumns(6)
                ->setChoices([
                    'Draft' => Article::ARTICLE_STATUS_DRAFT,
                    'To review' => Article::ARTICLE_STATUS_TO_REVIEW,
                    'Rejected' => Article::ARTICLE_STATUS_REJECTED,
                    'Published' => Article::ARTICLE_STATUS_PUBLISHED,
                    'Archived' => Article::ARTICLE_STATUS_ARCHIVED,
                ])

                ->setTemplatePath('crud/fields/status.html.twig'),
            yield DateTimeField::new('publishAt')->setColumns(3),

            yield FormField::addRow(),
            yield  BooleanField::new('Public')->setColumns('col-sm-2'),

        ];
    }
    public function configureFilters(Filters $filters): Filters
    {

        return parent::configureFilters($filters)
            ->add(EntityFilter::new('category'))
            ->add(DateTimeFilter::new('date'));
    }


    public function configureActions(Actions $actions): Actions
    {
        $draftAction = Action::new('draft', 'Draft', 'fas fa-edit text-warning')
            ->linkToCrudAction('draft');
        $reviewtAction = Action::new('review', 'Review', 'fas fa-hourglass-half text-primary')
            ->linkToCrudAction('review');
        $rejectAction = Action::new('reject', 'Reject', 'fas fa-ban text-danger')
            ->linkToCrudAction('reject');
        $publishAction = Action::new('publish', 'Publish', 'fas fa-paper-plane text-success')
            ->linkToCrudAction('publish');
        $archiveAction = Action::new('archive', 'Archive', 'fas fa-archive text-dark')
            ->linkToCrudAction('archive');



        return parent::configureActions($actions)
            ->add(Crud::PAGE_INDEX, $draftAction)
            ->add(Crud::PAGE_INDEX, $reviewtAction)
            ->add(Crud::PAGE_INDEX, $rejectAction)
            ->add(Crud::PAGE_INDEX, $publishAction)
            ->add(Crud::PAGE_INDEX, $archiveAction)
            ->setPermission('draft', 'draft')
            ->setPermission('review', 'review')
            ->setPermission('reject', 'reject')
            ->setPermission('publish', 'publish')
            ->setPermission('archive', 'archive');
    }
    /**
     * Undocumented function
     * @var Article $entity
     * @param AdminContext $adminContext
     * @return void
     */
    public function draft(AdminContext $adminContext)
    {
        $entity = $adminContext->getEntity()->getInstance();
        $entity->setStatus(Article::ARTICLE_STATUS_DRAFT);
        $this->entityManager->flush();
        return $this->redirect($adminContext->getReferrer());
    }

    public function review(AdminContext $adminContext)
    {
        $entity = $adminContext->getEntity()->getInstance();
        $entity->setStatus(Article::ARTICLE_STATUS_TO_REVIEW);
        $this->entityManager->flush();
        return $this->redirect($adminContext->getReferrer());
    }

    public function publish(AdminContext $adminContext)
    {
        $entity = $adminContext->getEntity()->getInstance();
        $entity->setStatus(Article::ARTICLE_STATUS_PUBLISHED);
        $this->entityManager->flush();
        return $this->redirect($adminContext->getReferrer());
    }

    public function archive(AdminContext $adminContext)
    {

        return $this->setStatus($adminContext, Article::ARTICLE_STATUS_ARCHIVED);
    }
    public function reject(AdminContext $adminContext)
    {

        return $this->setStatus($adminContext, Article::ARTICLE_STATUS_REJECTED);
    }

    public function setStatus(AdminContext $adminContext, String $status)
    {

        $entity = $adminContext->getEntity()->getInstance();
        $entity->setStatus($status);
        $this->entityManager->flush();
        return $this->redirect($adminContext->getReferrer());
    }
}
