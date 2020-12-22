<?php


class FormHandler
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

    public function authenticateClient()
    {
        $authenticationSuccess = false;
        if (isset($_POST["username"]) && $_POST["username"] != '') {

            /*  set variables  */
            $username = $_POST["username"];
            $password = $_POST["password"];

            /*    Client authentication   *///////////////////////////

            $ch = curl_init("https://apidev.questi.com/2.0/token");
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(array(
                'emailaddr' => $username,
                'passwrd' => "LR9K&rAr!S",
                'grant_type' => "password",
                'scope' => "sollicitatie-scope",
            )));

            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                "Content-Type: application/x-www-form-urlencoded",
                "Client-Id: q-sollicitatie-nifu",
                "Client-Secret: 73227d4d52422616170ecdb1857128a68d595e0a"));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_NOPROGRESS, 0);
            $response = curl_exec($ch);

            $decoded = json_decode($response);
            var_dump($decoded);

            if ($decoded->access_token === "") {
                echo "Invalid credentials - please try again";
            } else {
//                echo $decoded->access_token;
                curl_close($ch);

                /*  User authentication  *//////////////////////////

                if (isset($decoded) && $decoded != '') {

                    $ch2 = curl_init("https://apidev.questi.com/2.0/user");
                    curl_setopt($ch2, CURLOPT_URL, $this->api_url . '/user/');
                    curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch2, CURLOPT_HTTPHEADER, array(
                        "Content-Type: application/x-www-form-urlencoded",
                        "Client-Id: " . $this->client_id,
                        "Client-Secret: 73227d4d52422616170ecdb1857128a68d595e0a",
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
        // if the form and user data is valid

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
        $ch = curl_init("https://apidev.questi.com/2.0/user/eul");
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
        $_SESSION['eulHtml']= $jsonEulContent->result->eul_content;

    }


    public function calculateChecksum()
    {
        $date = date('Ymd');
        $client_secret = sha1(sha1($this->client_id . '_' . $this->client_secret_pre) . '_' . $date);
        return $client_secret;
    }

}