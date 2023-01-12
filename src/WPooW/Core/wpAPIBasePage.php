<?php




$CURRENT_VIEW_STATE = wpAPIPermissions::EditPage;

/**
 * Class wpAPIBasePage
 * The base page for the wpOOW application. Extended by PageTypes appropiately
 *
 * @package wpAPI\Core
 */
abstract class wpAPIBasePage
{

    protected $slug;
    protected $label;
    protected $parent_slug;
    private $viewState;

    function __construct($slug, $label)
    {
        $this->slug = sanitize_title_with_dashes($slug);
        $this->label = $label;
    }

    /**
     * Get the page slug/id
     * @return mixed
     */
    public function GetSlug()
    {
        return $this->slug;
    }

    /**
     * Calls the generate for given PageType and  given renderhook
     * @param $parent_slug
     */
    public function Render($parent_slug)
    {

        if ($parent_slug != null) {
            $this->parent_slug = $parent_slug;
        }

        add_action($this->RenderHook(), [$this, "Generate"], 8); #have to make priority lower than 10 to allow for none replacement when using custom post type. See note for show_in_menu @ https://codex.wordpress.org/Function_Reference/register_post_type
        add_action('current_screen', [$this, "LoadViewState"]);
    }

    /**
     * Set the viewstate of the currently viewed page.Called automatically with the PostType page type
     * @param null $screen
     */
    function LoadViewState($screen = null)
    {
        global $CURRENT_VIEW_STATE;

        $this->SetViewState($CURRENT_VIEW_STATE);
    }

    /**
     * Gets the view
     * @return mixed
     */
    protected function GetViewState()
    {
        return $this->viewState;
    }

    /**
     * Sets the viewstate. Used by none PostType
     * @param $vs
     */
    protected function SetViewState($vs)
    {
        global $CURRENT_VIEW_STATE;
        $CURRENT_VIEW_STATE = $vs;
        $this->viewState = $vs;
    }


    /**
     * @return mixed
     */
    abstract function Generate();

    /**
     * @return mixed
     */
    abstract function RenderHook(); //return what render hook to use

}
