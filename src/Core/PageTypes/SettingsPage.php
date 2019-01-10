<?php 

namespace  WPooW\Core\PageTypes;

use WPooW\Core\BasePage;
use WPooW\Auth\WPooWPermissions;
use WPooW\Core\ObjectCache;


class SettingsPage extends BasePage{

    protected $page_template;
    protected $capabilities;
    protected $position;
    protected $BeforeSaveEvent = [];
    protected $page_sections = [];

    function __construct($page_slug, $page_title, $capabilities, $heading="", $page_template=null, $icon = '', $position=null)
    {
        parent::__construct($page_slug, $page_title);
        $this->page_template = $page_template;
        $this->capabilities = $capabilities;
        $this->icon = $icon;
        $this->position = $position;
    }

    //TODO: Id's tp long maybe make them shorter
    protected function GetPageRegistryId(){
        return sprintf("wpoow_options_page_registry_id_%s", $this->slug);
    } 

    protected function GetPageId(){
        return sprintf("wpoow_options_page_%s", $this->slug);
    }

    protected function BeforeSave($data){
        $new_data=[];

        foreach ($this->BeforeSaveEvents as $observor) {
            foreach (call_user_func_array($observor, [$data]) as $key => $value) {
                $new_data[$key] = $value;
            }
        }

        return empty($new_data) ? $data : $new_data;
    }

    public function RegisterBeforeSaveEvent($method, $class=null)
    {
        if ($class == null)
        {
            array_push($this->BeforeSaveEvents,  $method);
        }
        else
        {
            array_push($this->BeforeSaveEvents, [$class, $method]);

        }
    }
    

    public function Render($parent_slug=null)
    {

        if ($parent_slug != null) {
            $this->parent_slug = $parent_slug;
        }

        add_action('admin_init',  [$this, "PrepareSetting"]);
        add_action('admin_menu', [$this, "Generate"], 8); 
        add_action('current_screen', [$this, "LoadViewState"]);
    }

    function Generate(){
        // options-general.php makes it fall on setting
        if ($parent_slug != null){
            add_submenu_page($this->parent_slug, $this->label, $this->label, $this->capability , $this->slug, [$this, "GenerateView"]);
        }
        else{
            add_menu_page($this->menu_title, $this->label,   $this->capability, $this->slug, [$this, "GenerateView"], $this->icon, $this->position);
        }

    }
    
    function GenerateView(){

        if ($this->page_template == null)
        {
            echo "Setting template";
        }
        $this->page_template->Render();
        
    }

    function PrepareSettings(){
        register_setting($this->GetPageRegistryId(), $this->GetPageRegistryId(), [$this, "BeforeSave"] );
        //TODO: if no section, create one, add field directly
        foreach($this->page_sections as $page_section){
            add_settings_section($page_section->id, $page_section->title, [$page_section, "GenerateView"], $this->GetPageId());
            $page_section->RenderFields();
        }
    }

    function AddSection($slug, $title, $page_template=null, $fields=[] ){
        $newSections = new SettingsSection($slug, $title, $page_template,  $fields, $this);
        array_push($this->page_sections, $newSections);
        return $newSections;
    }


}

class SettingsSection{

    public $id;
    public $slug;
    public $sectionsFields;
    public $title = "";
    public $page_template = null;
    public $parent_page;


    function __construct($slug, $title, $page_template, $fields, $parent_page){
        $this->slug = $slug;
        $this->title = $title;
        $this->page_template = $page_template;
        $this->id = sprintf("%s_%s", $parent_page->GetPageId(), $slug);
        $this->sectionsFields = $fields; //TODO: this might copy by reference
        $this->parent_page = $parent_page;
    }

    function AddField($aField){
        $aField->id = sprintf("%s_%s", $this->id, $aField->id);
        array_push($this->sectionsFields, $aField);
    }

    function RenderFields(){
        $set_options = get_options();
        foreach($this->sectionsFields as $aField){
            add_setting_field($aField->id, $aField->label, [$aField, "OptionsPageView"],  $this->parent_page->GetPageId(), [
                'options' => $set_options,
                'options_page_id' => $this->parent_page->GetPageRegistryId()
            ] );
        }
    }

    function GenerateView(){
        if ($this->page_template != null)
        {
            $this->page_template->Render();
        }
        
    }
}