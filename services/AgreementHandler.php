<?php


class AgreementHandler
{
    private $viewService;
    private $api_url = "https://apidev.questi.com/2.0";
    private $url = "http://localhost:8080";
    private $grant_type = 'password';
    private $scope = 'sollicitatie-scope';
    private $client_id = 'q-sollicitatie-nifu';
    private $client_secret_pre = '5Wlu8Fq3wSBxIPa4vB9AOGPCyQ8QwVw0w5MjFzTXj8pdeDWziG';


    public function __construct(ViewService $viewService)
    {
        $this->viewService = $viewService;
    }

    public function submitSignedAgreement()
    {
        $ch = curl_init("https://apidev.questi.com/2.0/user/eul");
        curl_setopt($ch, CURLOPT_URL, $this->api_url.'/user/eul');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Content-Type: application/x-www-form-urlencoded",
            "Client-Id: ".$this->client_id,
            "Client-Secret: ".$this->calculateChecksum(),
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
        return $submitSuccess;
    }

    public function processSignedAgreement()
    {
        if ($this->submitSignedAgreement())
        {
            header('Location: '.$this->url.'/profile.php');
        } else {
            print "could not process eul submission";
        }
    }

    public function calculateChecksum()
    {
        $date = date('Ymd');
        return sha1(sha1($this->client_id . '_' . $this->client_secret_pre) . '_' . $date);
    }
}