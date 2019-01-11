<?php 

namespace  WPooW\Core\PageTypes;

use WPooW\Core\BasePage;
use WPooW\Auth\WPooWPermissions;
use WPooW\Core\ObjectCache;
use WPooW\Utilities\CONSTS;

class SettingsPage extends BasePage{

    protected $page_template;
    protected $capabilities;
    protected $position;
    protected $BeforeSaveEvent = [];
    protected $page_sections = [];
    protected $heading;
    protected $description;

    function __construct($page_slug, $page_title, $capabilities, $heading="", $description="", $page_template=null, $icon = '', $position=null)
    {
        parent::__construct($page_slug, $page_title);
        $this->page_template = $page_template;
        $this->capabilities = $capabilities;
        $this->icon = $icon;
        $this->position = $position;
        $this->heading = $heading;
        $this->description = $description;
    }

    function RenderHook(){}

    function GetPageId(){
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

        add_action('admin_init',  [$this, "PrepareSettings"]);
        add_action('admin_menu', [$this, "Generate"], 8); 
        add_action('current_screen', [$this, "LoadViewState"]);
    }

    function Generate(){
        // options-general.php makes it fall on setting    
        if ($parent_slug != null){
            add_submenu_page($this->parent_slug, $this->label, $this->label, $this->capabilities , $this->slug, [$this, "GenerateView"]);
        }
        else{
            add_menu_page($this->label, $this->label,   $this->capabilities, $this->slug, [$this, "GenerateView"], $this->icon, $this->position);
        }

    }
    
    function GenerateView(){

        if ($this->page_template == null)
        {
            echo "<div>";
            echo $this->heading != "" ? sprintf("<h1>%s</h1>", $this->heading) : "";
            echo $this->description != "" ? sprintf("<p>%s</p>", $this->description) : "";
            echo "<form action='options.php' method='post'>";
            settings_fields($this->GetPageId());
            do_settings_sections($this->GetPageId());
            echo "<input name='Submit' type='Submit' value='".esc_attr('Save Changes')."'/>";
            echo "</form></div>";
        }
        else{
            $this->page_template->Render();
        }
        
        
    }

    function PrepareSettings(){
        register_setting($this->GetPageId(), $this->GetPageId(), [$this, "BeforeSave"] );
        //TODO: if no section, create one, add field directly
        foreach($this->page_sections as $page_section){
            add_settings_section($page_section->id, $page_section->title, [$page_section, "GenerateView"], $this->GetPageId());
            $page_section->RenderFields();
        }
    }

    function AddSection($slug, $title, $fields=[], $page_template=null ){
        $newSections = new SettingsSection($slug, $title, $page_template,  $fields, $this);
        array_push($this->page_sections, $newSections);
        return $newSections;
    }


}

class SettingsSection{

    public $id;
    public $slug;
    public $sectionsFields = [];
    public $title = "";
    public $page_template = null;
    public $parent_page;


    function __construct($slug, $title, $page_template, $fields, $parent_page){
        $this->slug = $slug;
        $this->title = $title;
        $this->page_template = $page_template;
        $this->id = sprintf("%s_%s", $parent_page->GetPageId(), $slug);
        $this->parent_page = $parent_page;
        foreach ($fields as $aField){$this->AddField($aField);}
                
    }

    //TODO: Add style
    function AddField($aField){
        $aField->option_id = $aField->id;
        $aField->id = sprintf("%s[%s]", $this->parent_page->GetPageId(), $aField->option_id);
        $aField->pageType = CONSTS::ELEMENT_PAGE_TYPE_SETTING_PAGE;
        array_push($this->sectionsFields, $aField);
    }

    function RenderFields(){
        $set_options = get_option($this->parent_page->GetPageId());
        
        //TODO: add documentation on pagetype for readview (switch between posttype and options page)
        foreach($this->sectionsFields as $aField){
            add_settings_field($aField->option_id, $aField->label, [$aField, "EditView"],  $this->parent_page->GetPageId(), $this->id, [
                'options' => $set_options == null ? [] : $set_options,
                'options_page_id' => $this->parent_page->GetPageId()
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