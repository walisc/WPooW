<?php

namespace WPooW\Utilities;


class HTMLTemplate{
    /**
     * Constant that lets StaticPage know that it should render the page based on a twig template file
     */
    CONST PATH = 1;
    /**
     * Constant that lets StaticPage know that it should render the page based on a template string
     */
    CONST CONTENT = 2;

    /**
     * Either HTMLTemplate::PATH or HTMLTemplate::CONTENT
     * @var
     */
    private $type;

    /**
     * When HTMLTemplate::PATH is used for type this links to the path of the twig template
     * When HTMLTemplate::CONTENT is used for type this is used as a string template for rendering the page
     * @var
     */

    private $path_content;

    /**
     *
     * key, value array to use in the twig template/string
     *
     * @var array
     */
    private $data = [];

    /**
     * StaticPage constructor.
     * @param $type
     * @param $path_content
     * @param $data
     */
    function __construct($type, $path_content, $data=[])
    {
        $this->type = $type;
        $this->path_content = $path_content;
        $this->data = array_merge($this->data, $data);

    }

    /**
     *
     * Method that renders the twig template
     *
     */
    function Render()
    {
        
        if ($this->type == self::PATH)
        {
            //TODO: Make this global

            $loader = new Twig_Loader_Filesystem(ABSPATH);
            $twig = new Twig_Environment($loader);

            echo $twig->render($this->path_content, $this->data);
        }
        else if ($this->type == self::CONTENT)
        {

            $loader = new Twig_Loader_Array(array(
                'page.html' => $this->path_content,
            ));

            $twig = new Twig_Environment($loader);

            echo $twig->render('page.html', $this->data);

        }



    }

    /**
     *
     * Allows you to set the key, value data to be used when rendering the view.
     * The data can either be appended to already existing data, or replace already existing data.
     *
     * @param $data
     * @param bool $append
     */
    function SetData($data, $append=true)
    {
        if ($append) {
            $this->data = array_merge($this->data, $data);
        }
        else
        {
            $this->data = $data;
        }

    }
}