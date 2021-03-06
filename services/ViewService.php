<?php


class ViewService
{

    private $databaseService;


    public function __construct(DatabaseService $databaseService)
    {
        $this->databaseService = $databaseService;
    }

    /**
     * @param string $css
     * @param string $header
     * @return void
     */

    public function basicHead( $css = "", $header )
    {
        global $_application_folder;
        global $login_form;

        $str_stylesheets = "";
        if ( is_array($css))
        {
            foreach( $css as $stylesheet )
            {
                $str_stylesheets .= '<link rel="stylesheet" href="' . $_application_folder . '/css/' . $stylesheet . '">' ;
            }
        }

        $data = array("stylesheets" => $str_stylesheets, "header" => $header);

        $template = $this->loadTemplate("basic_head");
        print $this->replaceContentOneRow($data, $template);

        $_SESSION["head_printed"] = true;
    }


    /**
     * @param $name
     * @return mixed
     */

    public function loadTemplate($name)
    {
        if ( file_exists("$name.html") ) return file_get_contents("$name.html");
        if ( file_exists("templates/$name.html") ) return file_get_contents("templates/$name.html");
        if ( file_exists("../templates/$name.html") ) return file_get_contents("../templates/$name.html");
    }

    /**
     * @param $row
     * @param $template_html
     * @return string
     */

    public function replaceContentOneRow($row, $template_html )
    {
        //replace fields with values in template
        $content = $template_html;
        foreach($row as $field => $value)
        {
            $content = str_replace("@@$field@@", $value, $content);
        }

        return $content;
    }


}