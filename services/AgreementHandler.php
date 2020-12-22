<?php


class AgreementHandler
{
    private $viewService;
    private $api_url;
    private $url;
    private $client_id;
    private $client_secret_pre;

    public function __construct(ViewService $viewService, array $clientCredentials)
    {
        $this->viewService = $viewService;
        $this->api_url = $clientCredentials['api_url'];
        $this->client_id = $clientCredentials['client_id'];
        $this->client_secret_pre = $clientCredentials['client_secret_pre'];
    }

    public function submitSignedAgreement()
    {
        $ch = curl_init($this->api_url."/user/eul");
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