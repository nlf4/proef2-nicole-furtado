<?php


class AgreementHandler
{
    private $viewService;
    /**
     * @var string
     */
    private $api_url;
    /**
     * @var string
     */
    private $url;
    /**
     * @var string
     */
    private $client_id;
    /**
     * @var string
     */
    private $client_secret;

    public function __construct(ViewService $viewService, array $clientCredentials, string $client_secret)
    {
        $this->viewService = $viewService;
        $this->client_secret = $client_secret;
        $this->api_url = $clientCredentials['api_url'];
        $this->client_id = $clientCredentials['client_id'];
    }

    /**
     * @return bool
     */
    public function submitSignedAgreement()
    {
        // update user eul data
        $ch = curl_init($this->api_url."/user/eul");
        curl_setopt($ch, CURLOPT_URL, $this->api_url.'/user/eul');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Content-Type: application/x-www-form-urlencoded",
            "Client-Id: ".$this->client_id,
            "Client-Secret: ".$this->client_secret,
            "Authorization: Bearer " . $_SESSION["token"]
        ));
        curl_setopt($ch, CURLOPT_POSTFIELDS,http_build_query(array(
            'signed_eul' => true,
        )));

        $response = curl_exec($ch);
        curl_close($ch);
        $submitSuccess = true;
        $_SESSION['show_modal'] = false;
        $_SESSION['eulSubmitResponse'] = json_decode($response);
        $_SESSION['signed_agreement'] = 1;
        return $submitSuccess;
    }

    public function processSignedAgreement()
    {
        // direct back to profile on success
        if ($this->submitSignedAgreement())
        {
            header('Location: '.$this->url.'/profile.php');
        } else {
            print "could not process eul submission";
        }
    }

}