<?php


class Container
{
    private $pdo;

    private $databaseService;

    private $viewService;

    private $formHandler;

    private $agreementHandler;
    /**
     * @var array
     */
    private $credentialConfig;
    /**
     * @var array
     */
    private $dbConfig;


    public function __construct(array $dbConfig, array $credentialConfig)
    {
        $this->dbConfig = $dbConfig;
        $this->credentialConfig = $credentialConfig;
    }

    /**
     * @return PDO
     */
    public function getPDO()
    {
        if ($this->pdo === null) {
            $this->pdo = new PDO(
                $this->dbConfig['db_dsn'],
                $this->dbConfig['db_user'],
                $this->dbConfig['db_pass']
            );
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }

        return $this->pdo;
    }

    public function calculateChecksum()
    {
        $date = date('Ymd');
        return sha1(sha1($this->credentialConfig['client_id'] . '_' . $this->credentialConfig['client_secret_pre']) . '_' . $date);
    }

    public function getDatabaseService()
    {
        if ($this->databaseService === null) {
            $this->databaseService = new databaseService($this->getPDO());
        }

        return $this->databaseService;
    }

    public function getFormHandler()
    {
        if ($this->formHandler === null) {
            $this->formHandler = new FormHandler(
                $this->getViewService(),
                $this->credentialConfig,
                $this->calculateChecksum()
            );
        }

        return $this->formHandler;
    }

    public function getAgreementHandler()
    {
        if ($this->agreementHandler === null) {
            $this->agreementHandler = new AgreementHandler(
                $this->getViewService(),
                $this->credentialConfig,
                $this->calculateChecksum()
            );
        }

        return $this->agreementHandler;
    }

    public function getViewService()
    {
        if ($this->viewService === null) {
            $this->viewService = new ViewService($this->getDatabaseService());
        }

        return $this->viewService;
    }


}