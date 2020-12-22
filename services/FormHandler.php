<?php


class FormHandler
{

    private $viewService;
    private $api_url;
    private $url;
    private $grant_type;
    private $scope;
    private $client_id;
    private $client_secret_pre;

    public function __construct(ViewService $viewService, array $clientCredentials)
    {
        $this->viewService = $viewService;
        $this->api_url = $clientCredentials['api_url'];
        $this->url = $clientCredentials['url'];
        $this->grant_type = $clientCredentials['grant_type'];
        $this->scope = $clientCredentials['scope'];
        $this->client_id = $clientCredentials['client_id'];
        $this->client_secret_pre = $clientCredentials['client_secret_pre'];
    }

    public function authenticateClient()
    {
        $authenticationSuccess = false;
        if (isset($_POST["username"]) && $_POST["username"] != '') {

            /*  set variables  */
            $username = $_POST["username"];
            $password = $_POST["password"];

            /*    Client authentication   *///////////////////////////
            $ch = curl_init($this->api_url."/token");
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(array(
                'emailaddr' => $username,
                'passwrd' => $password,
                'grant_type' => $this->grant_type,
                'scope' => $this->scope,
            )));

            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                "Content-Type: application/x-www-form-urlencoded",
                "Client-Id: ".$this->client_id,
                "Client-Secret: ".$this->calculateChecksum()));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_NOPROGRESS, 0);
            $response = curl_exec($ch);

            $decoded = json_decode($response);
//            var_dump($decoded);

            if (!isset($decoded->access_token)) {
                echo "Invalid credentials - please try again";
                die();
            } else {
//                echo $decoded->access_token;
                curl_close($ch);

                /*  User authentication  *//////////////////////////

                if (isset($decoded) && $decoded !== '') {

                    $ch2 = curl_init($this->api_url."/user");
                    curl_setopt($ch2, CURLOPT_URL, $this->api_url . '/user/');
                    curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch2, CURLOPT_HTTPHEADER, array(
                        "Content-Type: application/x-www-form-urlencoded",
                        "Client-Id: " . $this->client_id,
                        "Client-Secret: ".$this->calculateChecksum(),
                        "Authorization: Bearer " . $decoded->access_token
                    ));

                    $userData = curl_exec($ch2);
                    curl_close($ch2);

                    $jsonArrayResponse = json_decode($userData);
                    var_dump($jsonArrayResponse);
                    if ($jsonArrayResponse->status === "success") {
                        $userName = $jsonArrayResponse->result->user_name;
                        $userFirstName = $jsonArrayResponse->result->user_firstname;
                        $userEmail = $jsonArrayResponse->result->user_email;
                        $userMobile = $jsonArrayResponse->result->user_mobile;
                        $userLang = $jsonArrayResponse->result->user_language;
                        $signed_agreement = $jsonArrayResponse->result->signed_agreement;

                        $_SESSION['user_name'] = $userName;
                        $_SESSION['user_firstname'] = $userFirstName;
                        $_SESSION['user_email'] = $userEmail;
                        $_SESSION['user_mobile'] = $userMobile;
                        $_SESSION['user_lang'] = $userLang;
                        $_SESSION['signed_agreement'] = $signed_agreement;
                        $_SESSION['token'] = $decoded->access_token;
//                        var_dump($_SESSION);
                        $authenticationSuccess = true;
                        if($_SESSION['signed_agreement'] === 0)
                        {
                            $_SESSION['show_modal'] = true;
                        } else {
                            $_SESSION['show_modal'] = false;
                        }
                    } else {
                        echo "User data could not be retrieved";
                    }
                }
            }
        }
        return $authenticationSuccess;
    }

    public function processLogIn()
    {
        if ($this->authenticateClient())
        {
            if($this->getEulContent() !== 0)
            {
                header('Location: '.$this->url.'/profile.php');
            } else {
                print "could not get eul text";
            }
        } else {
            print "could not process login";
        }
    }

    public function getEulContent()
    {
        if ($_SESSION['signed_agreement'] === 0) {
            $ch = curl_init($this->api_url."/user/eul");
            curl_setopt($ch, CURLOPT_URL, $this->api_url . '/user/eul');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                "Content-Type: application/x-www-form-urlencoded",
                "Client-Id: " . $this->client_id,
                "Client-Secret: " . $this->calculateChecksum(),
                "Authorization: Bearer " . $_SESSION['token']
            ));

            $eulContent = curl_exec($ch);
            curl_close($ch);

            $jsonEulContent = json_decode($eulContent);
            $_SESSION['eulHtml'] = $jsonEulContent->result->eul_content;
        } else {
            return true;
        }
    }


    public function calculateChecksum()
    {
        $date = date('Ymd');
        return sha1(sha1($this->client_id . '_' . $this->client_secret_pre) . '_' . $date);
    }

}