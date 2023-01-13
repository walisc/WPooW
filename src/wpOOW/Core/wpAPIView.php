<?php

namespace wpOOW\Core;
/**
 *
 * Class responsible for rendering wp-admin menu pages. This uses the php template engine twig.
 * A wpAPIVIEW can either be linked to a twig template or be based on a template string
 *
 * Class wpAPIVIEW
 * @package wpAPI\Base
 */
class wpAPIView
{
    /**
     * Constant that lets wpAPIVIEW know that it should render the page based on a twig template file
     */
    CONST PATH = 1;
    /**
     * Constant that lets wpAPIVIEW know that it should render the page based on a template string
     */
    CONST CONTENT = 2;

    /**
     * Either wpAPIVIEW::PATH or wpAPIVIEW::CONTENT
     * @var
     */
    private $type;

    /**
     * When wpAPIVIEW::PATH is used for type this links to the path of the twig template
     * When wpAPIVIEW::CONTENT is used for type this is used as a string template for rendering the page
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
     *
     * base path to use when finding templates. If not specified use ABSPATH
     *
     * @var array
     */
    private $base_path = [];


    /**
     * wpAPIVIEW constructor.
     * @param $type
     * @param $path_content
     * @param $data
     * @param $base_path
     */
    function __construct($type, $path_content, $data=[], $base_path=null)
    {
        $this->type = $type;
        $this->path_content = $path_content;
        $this->data = array_merge($this->data, $data);
        $this->base_path = $base_path != null ? $base_path : ABSPATH;

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

            $loader = new \Twig\Loader\FilesystemLoader($this->base_path);
            $twig = new \Twig\Environment($loader);

            echo $twig->render($this->path_content, $this->data);
        }
        else if ($this->type == self::CONTENT)
        {

            $loader = new \Twig\Loader\ArrayLoader(array(
                'page.html' => $this->path_content,
            ));

            $twig = new \Twig\Environment($loader);

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



