<?php


class AbstractUser
{
    private $id;
    private $name;
    private $firstname;
    private $email;
    private $signed_agreement;
    private $user_language;
    private $user_level;
    private $user_force_password;
    private $user_mobile;
    private $user_tel;
    private $profile_img;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * @param string $firstname
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return int
     */
    public function getSignedAgreement()
    {
        return $this->signed_agreement;
    }

    /**
     * @param int $signed_agreement
     */
    public function setSignedAgreement($signed_agreement)
    {
        $this->signed_agreement = $signed_agreement;
    }

    /**
     * @return string
     */
    public function getUserLanguage()
    {
        return $this->user_language;
    }

    /**
     * @param string $user_language
     */
    public function setUserLanguage($user_language)
    {
        $this->user_language = $user_language;
    }

    /**
     * @return int
     */
    public function getUserLevel()
    {
        return $this->user_level;
    }

    /**
     * @param int $user_level
     */
    public function setUserLevel($user_level)
    {
        $this->user_level = $user_level;
    }

    /**
     * @return bool
     */
    public function getUserForcePassword()
    {
        return $this->user_force_password;
    }

    /**
     * @param bool $user_force_password
     */
    public function setUserForcePassword($user_force_password)
    {
        $this->user_force_password = $user_force_password;
    }

    /**
     * @return string
     */
    public function getUserMobile()
    {
        return $this->user_mobile;
    }

    /**
     * @param string $user_mobile
     */
    public function setUserMobile($user_mobile)
    {
        $this->user_mobile = $user_mobile;
    }

    /**
     * @return string
     */
    public function getUserTel()
    {
        return $this->user_tel;
    }

    /**
     * @param string $user_tel
     */
    public function setUserTel($user_tel)
    {
        $this->user_tel = $user_tel;
    }

    /**
     * @return string
     */
    public function getProfileImg()
    {
        return $this->profile_img;
    }

    /**
     * @param string $profile_img
     */
    public function setProfileImg($profile_img)
    {
        $this->profile_img = $profile_img;
    }

}