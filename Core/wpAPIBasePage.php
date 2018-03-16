<?php

$CURRENT_VIEW_STATE = wpAPIPermissions::EditPage;

/**
 *
 * Base Page for all wpAPI admin back pages (PostType, Menu, or SubMenu)
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
        $this->slug = $slug;
        $this->label = $label;
    }

    public function GetSlug()
    {
        return $this->slug;
    }

    public function Render($parent_slug)
    {

        if ($parent_slug != null) {
            $this->parent_slug = $parent_slug;
        }

        add_action($this->RenderHook(), [$this, "Generate"], 8); #have to make priority lower than 10 to allow for none replacement when using custom post type. See note for show_in_menu @ https://codex.wordpress.org/Function_Reference/register_post_type
        add_action('current_screen', [$this, "LoadViewState"]);
    }

    function LoadViewState($screen = null)
    {
        global $CURRENT_VIEW_STATE;

        $this->SetViewState($CURRENT_VIEW_STATE);
    }

    protected function GetViewState()
    {
        return $this->viewState;
    }

    protected function SetViewState($vs)
    {
        global $CURRENT_VIEW_STATE;
        $CURRENT_VIEW_STATE = $vs;
        $this->viewState = $vs;
    }


    abstract function Generate();
    abstract function RenderHook(); //return what render hook to use

}
