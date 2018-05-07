<?php

/**
 * Created by PhpStorm.
 * User: chidow
 * Date: 2016/08/13
 * Time: 7:36 PM
 */

abstract class BaseElement
{
    //TODO: Have a way of checking that id is unique
    public $parent_slug;
    public $id;
    public $label;
    public $permissions;
    protected $twigTemplate;
    protected $cssClasses;

    public $saveFunction;
    public $saveNonce;
    public $valueKey;

    private $onSaveEvents = [];
    private $onReadEvents = [];

    protected $ScriptHandler = "wpAPIBaseElementJS";
    protected $CssHandler = "wpAPIBaseElementCss";


    // for component. Use in BaseScriptsToLoad to load method
    protected function EnqueueElementBaseScript($handle, $src, $shared_variable = [], $deps = [], $ver = false, $in_footer = false )
    {
        $this->ScriptHandler = $handle;
        wp_register_script($this->ScriptHandler ,  $src,  $deps, $ver, $in_footer);

        if (!empty($shared_variable)) {
            wp_localize_script($this->ScriptHandler, sprintf("%s_Data_Bag", get_class($this)), $shared_variable);
        }
        wp_enqueue_script($this->ScriptHandler);

    }
    
    //Use in BaseScriptsToLoad to load method
    protected function EnqueueElementBaseCSS($handle, $src, $deps = array(), $ver = false, $media = 'all' )
    {
        $this->CssHandler = $handle;
        wp_enqueue_style($this->CssHandler, $src, $deps, $ver, $media);
    }

    // for instance. Use on element render. For read or write
    protected function EnqueueElementScript($src_path, $shared_variables= [], $handler=null)
    {
        wp_add_inline_script($handler ? $handler : $this->ScriptHandler, $this->twigTemplate->render($src_path, $shared_variables)) ;
    }

    /*protected function EnqueueElementCSS($src_path, $shared_variables= [])
    {
        // This adds the css where the element is defined and is a anti-pattern. Don't use. Just here for reference sake
        wp_styles()->registered[$this->CssHandler]->add_data( 'after', [$this->twigTemplate->render($src_path, $shared_variables)] );
        wp_styles()->print_inline_style($this->CssHandler);
    }*/
    
    protected function ReadView($post)
    {
        echo $this->GetDatabaseValue($post);
    }
    protected function EditView( $post)
    {
        wp_nonce_field($this->saveFunction, $this->saveNonce);
    }

    protected function SaveElementData($post_id, $data)
    {
        $processed_data = $data;

        // Call Save observers before updating
        foreach ($this->onSaveEvents as $observor)
        {
            $processed_data =  call_user_func_array($observor, [$processed_data, $post_id]);
        }

        update_post_meta($post_id, $this->valueKey, $processed_data);
    }

    protected function GetDatabaseValue($post, $single = true)
    {
        //When viewing the table/grid post_id is passed instead of the WP_POST object
        $post_id = is_numeric($post) ? $post : $post->ID;
        $db_value =  get_post_meta($post_id, $this->valueKey, $single);

        // Call Read Observer before reading/viewing the value
        foreach ($this->onReadEvents as $observor)
        {
            $db_value = wpAPIUtilities::CallUserFunc($observor[0], $observor[1], [$db_value, $post_id] );
        }
        return $db_value;
    }

    protected function GetElementDirectory()
    {
        return WP_API_ELEMENT_PATH_REL.  get_class($this) . DIRECTORY_SEPARATOR;
    }

    protected function GetElementURIDirectory()
    {
        return WP_API_ELEMENT_URI_PATH.  get_class($this) . URL_SEPARATOR;
    }

    // register events
    public function RegisterOnSaveEvent($class, $method)
    {
        array_push($this->onSaveEvents, [$class, $method ]);
    }

    public function RegisterOnReadEvent($class, $method)
    {

        array_push($this->onReadEvents, [$class, $method ]);
    }


    public function ProcessPostData($post_id)
    {
        if ((!isset($_POST[$this->saveNonce])
        || (! wp_verify_nonce($_POST[$this->saveNonce], $this->saveFunction))
        || (defined("DOING_AUTOSAVE") && DOING_AUTOSAVE)
        || (! current_user_can('edit_post', $post_id))
        )){return;}
     }

    function __construct($id, $label="", $permissions=[], $elementPath = '', $elementCssClasses=[])
    {
        $this->id = $id;
        $this->label = $label;
        $this->permissions= wpAPIPermissions::SetPermission($permissions);
        $this->cssClasses = $elementCssClasses;
        $this->saveFunction = sprintf("save_data_%s",  $this->id);
        $this->saveNonce = sprintf("%s_meta_box_nonce",$this->id);
        $this->valueKey = sprintf("%s_value_key", $this->id);

        //TODO: Make this global

        $loader = new Twig_Loader_Filesystem(dirname((new ReflectionClass($this))->getFileName()). $elementPath);
        $this->twigTemplate = new Twig_Environment($loader);

        wpAPIObjects::GetInstance()->AddObject(sprintf("_element_%s", $this->id), $this);

        add_action( 'admin_enqueue_scripts', [$this, "loadScripts" ] );

    }

    //override in element to base scripts
    protected function BaseScriptsToLoad(){}

    function loadScripts(){
        wp_register_script($this->ScriptHandler,  WP_API_ELEMENT_URI_PATH  . "wpOOWBaseElement.js",  ["jquery"], "1.0.0", true);
        wp_enqueue_script($this->ScriptHandler);

        wp_register_style($this->CssHandler,  WP_API_ELEMENT_URI_PATH  . "wpOOWBaseElement.css",  [""], "1.0.0");
        wp_enqueue_style($this->CssHandler);

        $this->BaseScriptsToLoad();
    }
}


