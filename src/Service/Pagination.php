<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use Twig\Environment;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Classe de pagination qui extrait toute notion de calcul et de récupération de données de nos controllers
 *
 * Elle nécessite après instanciation qu'on lui passe l'entité sur laquelle on souhaite travailler
 */
class Pagination {
    /**
     * The name of the entity on which we want to perform a pagination
     *
     * @var string
     */
    private $entityClass;

    /**
     * The number of records to retrieve
     *
     * @var integer
     */
    private $limit = 10;

    /**
     * The page we are currently on
     *
     * @var integer
     */
    private $currentPage = 1;

    /**
     * The Doctrine manager who allows us in particular to find the repository we need
     *
     * @var EntityManagerInterface
     */
    private $manager;

    /**
     * The Twig template engine which will generate the rendering of the pagination
     *
     * @var Twig\Environment
     */
    private $twig;

    /**
     * The name of the road that you want to use for the navigation buttons
     *
     * @var string
     */
    private $route;

    /**
     * The path to the template that contains the pagination
     *
     * @var string
     */
    private $templatePath;

    /**
     *
     * @param EntityManagerInterface $manager
     * @param Environment $twig
     * @param RequestStack $request
     * @param string $templatePath
     */
    public function __construct(EntityManagerInterface $manager, Environment $twig, RequestStack $request, string $templatePath) {
        // We retrieve the name of the route to use from the attributes of the current request
        $this->route        = $request->getCurrentRequest()->attributes->get('_route');
    
        $this->manager      = $manager;
        $this->twig         = $twig;
        $this->templatePath = $templatePath;
    }

    /**
     * Allows you to display the navigation rendering within a template Twig
     *
     *
     * @return void
     */
    public function display() {
        $this->twig->display($this->templatePath, [
            'page' => $this->currentPage,
            'pages' => $this->getPages(),
            'route' => $this->route
        ]);
    }

    /**
     * Allows you to retrieve the number of pages that exist on a particular entity
     *
     * @throws Exception si la propriété $entityClass n'est pas configurée
     *
     * @return int
     */
    public function getPages(): int {
        if(empty($this->entityClass)) {
            // If there is no entity configured, we cannot load the repository, the function
            // therefore cannot continue
            throw new \Exception("Vous n'avez pas spécifié l'entité sur laquelle nous devons paginer ! Utilisez la méthode setEntityClass() de votre objet PaginationService !");
        }

        
        $total = count($this->manager
            ->getRepository($this->entityClass)
            ->findAll());

   
        return ceil($total / $this->limit);
    }

    /**
     * Allows you to retrieve paginated data for a specific entity
     *
     *
     * @throws Exception if the $ entityClass property is not set
     *
     * @return array
     */
    public function getData() {
        if(empty($this->entityClass)) {
            throw new \Exception("Vous n'avez pas spécifié l'entité sur laquelle nous devons paginer ! Utilisez la méthode setEntityClass() de votre objet PaginationService !");
        }
        // 1) Calcul offset
        $offset = $this->currentPage * $this->limit - $this->limit;

        // 2) Ask the repository to find the elements from an offset and
        //    within the limit of elements imposed (see property $ limit)
        return $this->manager
            ->getRepository($this->entityClass)
            ->findBy([], [], $this->limit, $offset);
    }

    /**
     * 
     *  Allows you to specify the page you want to display
     *
     * @param int $page
     * @return self
     */
    public function setPages(int $page): self {
        $this->currentPage = $page;

        return $this;
    }

    /**
     * Allows you to retrieve the page that is currently displayed
     *
     * @return int
     */
    public function getPage(): int {
        return $this->currentPage;
    }

    /**
     * Allows you to specify the number of records you want to obtain!
     *
     * @param int $limit
     * @return self
     */
    public function setLimit(int $limit): self {
        $this->limit = $limit;

        return $this;
    }

    /**
     * Allows you to retrieve the number of records that will be returned
     *
     * @return int
     */
    public function getLimit(): int {
        return $this->limit;
    }

    /**
     * Allows you to specify the entity on which you want to paginate
     *
     * @param string $entityClass
     * @return self
     */
    public function setEntityClass(string $entityClass): self {
        $this->entityClass = $entityClass;

        return $this;
    }

    /**
     * Allows to retrieve the entity on which we are paging
     *
     * @return string
     */
    public function getEntityClass(): string {
        return $this->entityClass;
    }

    /**
     * Allows you to choose a pagination template
     *
     * @param string $templatePath
     * @return self
     */
    public function setTemplatePath(string $templatePath): self {
        $this->templatePath = $templatePath;

        return $this;
    }

    /**
     * Allows you to retrieve the currently used templatePath
     *
     * @return string
     */
    public function getTemplatePath(): string {
        return $this->templatePath;
    }

    /**
     * Allows you to change the default route for navigation links
     *
     * @param string $route
     * @return self
     */
    public function setRoute(string $route): self {
        $this->route = $route;

        return $this;
    }

    /**
     * Allows you to retrieve the name of the route which will be used on the navigation links
     *
     * @return string
     */
    public function getRoute(): string {
        return $route;
    }
}

