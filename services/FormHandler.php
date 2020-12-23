<?php


class FormHandler
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
    private $grant_type;
    /**
     * @var string
     */
    private $scope;
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
        $this->url = $clientCredentials['url'];
        $this->grant_type = $clientCredentials['grant_type'];
        $this->scope = $clientCredentials['scope'];
        $this->client_id = $clientCredentials['client_id'];
    }

    /**
     * @return bool
     */
    public function authenticateClient()
    {
        $authenticationSuccess = false;
        if (isset($_POST["username"]) && $_POST["username"] != '') {

            /*  get form fields  */
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
                "Client-Secret: ".$this->client_secret));
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
                        "Client-Secret: ".$this->client_secret,
                        "Authorization: Bearer " . $decoded->access_token
                    ));

                    $userData = curl_exec($ch2);
                    curl_close($ch2);

                    $jsonArrayResponse = json_decode($userData);
//                    var_dump($jsonArrayResponse);

                    // set session variables
                    if ($jsonArrayResponse->status === "success") {
                        $_SESSION['user_name'] = $jsonArrayResponse->result->user_name;
                        $_SESSION['user_firstname'] = $jsonArrayResponse->result->user_firstname;
                        $_SESSION['user_email'] = $jsonArrayResponse->result->user_email;
                        $_SESSION['user_mobile'] = $jsonArrayResponse->result->user_mobile;
                        $_SESSION['user_lang'] = $jsonArrayResponse->result->user_language;
                        $_SESSION['signed_agreement'] = $jsonArrayResponse->result->signed_agreement;
                        $_SESSION['token'] = $decoded->access_token;

//                        var_dump($_SESSION);
                        $authenticationSuccess = true;

                        //if agreement not signed, show modal
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

    /**
     * @return mixed
     */
    public function processLogIn()
    {
        // direct to profile on success
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

    /**
     * @return bool
     */
    public function getEulContent()
    {
        // get user agreement content if needed and set to session variable
        if ($_SESSION['signed_agreement'] === 0) {
            $ch = curl_init($this->api_url."/user/eul");
            curl_setopt($ch, CURLOPT_URL, $this->api_url . '/user/eul');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                "Content-Type: application/x-www-form-urlencoded",
                "Client-Id: " . $this->client_id,
                "Client-Secret: " . $this->client_secret,
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




}