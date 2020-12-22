<?php


class Container
{
    private $configuration;

    private $pdo;

    private $databaseService;

    private $viewService;

    private $userService;

    private $formHandler;


    public function __construct(array $configuration)
    {
        $this->configuration = $configuration;
    }

    /**
     * @return PDO
     */
    public function getPDO()
    {
        if ($this->pdo === null) {
            $this->pdo = new PDO(
                $this->configuration['db_dsn'],
                $this->configuration['db_user'],
                $this->configuration['db_pass']
            );
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }

        return $this->pdo;
    }

    public function getDatabaseService()
    {
        if ($this->databaseService === null) {
            $this->databaseService = new databaseService($this->getPDO());
        }

        return $this->databaseService;
    }
//
//    public function getUserService()
//    {
//        if ($this->userService === null) {
//            $this->userService = new UserService($this->getDatabaseService(), $this->getFormHandler(),$this->viewService,$this->getUploadService());
//        }
//
//        return $this->userService;
//    }
//
    public function getFormHandler()
    {
        if ($this->formHandler === null) {
            $this->formHandler = new FormHandler($this->getViewService());
        }

        return $this->formHandler;
    }



    public function getViewService()
    {
        if ($this->viewService === null) {
            $this->viewService = new ViewService($this->getDatabaseService());
        }

        return $this->viewService;
    }


}