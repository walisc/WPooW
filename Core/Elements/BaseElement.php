<?php


/**
 * Class BaseElement
 * The base class used by all wpOOW elements which contains abstract classes that need to be overwritten
 *
 * @package wpAPI\Core\Elements
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
    /**
     * Used to add base script for the element. This script will be shared by all instances for this type.
     * Only call this withing the BaseScriptsToLoad method as you will get `doing_it_wrong` word press error
     *
     * @param $handle
     * @param $src
     * @param array $shared_variable
     * @param array $deps
     * @param bool $ver
     * @param bool $in_footer
     */
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
    /**
     * Used to add base css for the element. The css will be shared by all instances for this type.
     * Only call this withing the BaseScriptsToLoad method as you will get `doing_it_wrong` word press error
     *
     * @param $handle
     * @param $src
     * @param array $deps
     * @param bool $ver
     * @param string $media
     */
    protected function EnqueueElementBaseCSS($handle, $src, $deps = array(), $ver = false, $media = 'all' )
    {
        $this->CssHandler = $handle;
        wp_enqueue_style($this->CssHandler, $src, $deps, $ver, $media);
    }

    // for instance. Use on element render. For read or write
    /**
     * Used to and a instance specific script. The script will be added for each instance of the element.
     * Also not this script will be added inline
     *
     * @param $src_path
     * @param array $shared_variables
     * @param null $handler - has to be registered!
     */
    protected function EnqueueElementScript($src, $shared_variables= [], $handler=null)
    {
        wp_add_inline_script($handler ? $handler : $this->ScriptHandler, $this->twigTemplate->render($src, $shared_variables)) ;
    }

    /**
     * Called when generating the read only version of the element is being rendered. You can the twig templating to render a more complex element
     * And example of this can be seen below
     *
     *  `  function ReadView($post)
     *      {
     *      echo $this->twigTemplate->render('/read_view.mustache', ["value" => $this->GetDatabaseValue($post)]);
     *      }
     *  `
     *
     *  Note the render path is relative to he current location, so read_view.mustache will be in the same directory as element
     *
     * @param $post
     */
    protected function ReadView($post)
    {
        echo $this->GetDatabaseValue($post);
    }

    /**
     * Called when generating the edit version of the element is being rendered. You can the twig templating to render a more complex element
     * And example of this can be seen below
     *
     *  `   function EditView( $post)
     *      {
     *          parent::EditView($post);
     *          echo $this->twigTemplate->render('/edit_view.mustache', [
     *          "id" => $this->id,
     *          "label" => $this->label,
     *          "value" => $this->GetDatabaseValue($post)
     *          ]);
     *      }
     *  `
     *
     *  Note the render path is relative to he current location, so read_view.mustache will be in the same directory as element
     *  Also note you need to call the parent method to generate the element wp_nonce, else it wont save correctly
     *
     * @param $post
     */
    protected function EditView($post)
    {
        wp_nonce_field($this->saveFunction, $this->saveNonce);
    }

    /**
     * Call this method to actually save the data in the database. This should be called within the ProcessPostData `methood`
     * which is automatically called when data is posted back
     *
     * @param $post_id
     * @param $data
     */
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

    /**
     * Method for getting the data value for the element for a given post id. Mainly used in the ReadView and EditView methods
     *
     * @param $post
     * @param bool $single
     * @return mixed
     */
    public function GetDatabaseValue($post, $single = true)
    {
        //When viewing the table/grid post_id is passed instead of the WP_POST object
        $post_id = is_numeric($post) ? $post : $post->ID;
        $db_value =  get_post_meta($post_id, $this->valueKey, $single);

        // Call Read Observer before reading/viewing the value
        // TODO: Consider removing this
        foreach ($this->onReadEvents as $observor)
        {
            $db_value = wpAPIUtilities::CallUserFunc($observor[0], $observor[1], [$db_value, $post_id] );
        }
        return $db_value;
    }

    /**
     * Method to the directory of the element folder
     *
     * @return string
     */
    protected function GetElementDirectory()
    {
        return WP_API_ELEMENT_PATH_REL.  get_class($this) . DIRECTORY_SEPARATOR;
    }

    /**
     * Method the get the URI Directory of the element folder
     *
     * @return string
     */
    protected function GetElementURIDirectory()
    {
        return WP_API_ELEMENT_URI_PATH.  get_class($this) . URL_SEPARATOR;
    }

    // register events
    //TODO: Change this method signature. method should be the first parameter amd class should default to null
    //TODO: Actually maybe consider removing them as you could always the RegisterBeforeSaveEvent/RegisterAfterSaveEvent in PostType
     /**
     * Method to register a before save method. This method will be called before data is saved. Useful if you want to modify some values, or auto populate them
     *
     * @param $class
     * @param $method
     */
    public function RegisterOnSaveEvent($class, $method)
    {
        array_push($this->onSaveEvents, [$class, $method ]);
    }

    /**
     * Method to register a before read method. This method will be called before data is rendered
     *
     * @param $class
     * @param $method
     */
    public function RegisterOnReadEvent($class, $method)
    {
        array_push($this->onReadEvents, [$class, $method ]);
    }


    /**
     * Called before saving data. Should be overriden by inheriting elements to process data accordingly, whilst still
     * calling this base method. to ensure that it that data is being saved securely
     * And example of this
     *
     *      ` function ProcessPostData($post_id)
     *       {
     *           parent::ProcessPostData($post_id);
     *           $data = sanitize_text_field($_POST[$this->id]);
     *
     *           $this->SaveElementData($post_id, $data);
     *
     *       }`
     *
     * @param $post_id
     */
    public function ProcessPostData($post_id)
    {
        if ((!isset($_POST[$this->saveNonce])
        || (! wp_verify_nonce($_POST[$this->saveNonce], $this->saveFunction))
        || (defined("DOING_AUTOSAVE") && DOING_AUTOSAVE)
        || (! current_user_can('edit_post', $post_id))
        )){return;}
     }

    /**
     * Constructor for the a base element
     *
     * BaseElement constructor.
     * @param $id
     * @param string $label
     * @param array $permissions
     * @param string $elementPath
     * @param array $elementCssClasses
     */
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
    /**
     * Called when wordpress enqueues scripts. Enqueue scripts using this method, else you will get a `doing_it_wrong` error
     * This can be using in conjunction with the `EnqueueElementBaseScript` and the `EnqueueElementBaseCSS` method
     *
     */
    protected function BaseScriptsToLoad(){}

    /**
     * Direct method called by wordpress when loading scripts
     *
     */
    function loadScripts(){
        wp_register_script($this->ScriptHandler,  WP_API_ELEMENT_URI_PATH  . "wpOOWBaseElement.js",  ["jquery"], "1.0.0", true);
        wp_enqueue_script($this->ScriptHandler);

        wp_register_style($this->CssHandler,  WP_API_ELEMENT_URI_PATH  . "wpOOWBaseElement.css",  [""], "1.0.0");
        wp_enqueue_style($this->CssHandler);

        $this->BaseScriptsToLoad();
    }

    /**
     * Formats the value for the data used on fetch
     *
     */
    function FormatForFetch($value, $recordId){
        return $value;
    }
}


