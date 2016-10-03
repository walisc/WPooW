# Wordpress API
#### An OOP Wordpress wrapper for rapid development

If you have ever had to create a custom theme in Wordpress you know how cumbersome it can be. This is a Wordpress API
wrapper that will enable you to create themes/plugins much faster in an object oriented way. Below is an example of how you can
create a custom post page along with additional metaboxes in a few lines of code.

```<?php

include 'src/wpAPI/wpAPI.php';

$wp_api = new wpAPI();

$menu_base = $wp_api->CreateMenu("wpAPI",
    "WP API", wpAPI_PERMISSIONS::MANAGE_OPTIONS,
    new wpAPI_VIEW(wpAPI_VIEW::PATH, "src/wpAPI/Templates/example.mustache", []));

$sub_menu = $wp_api->CreateSubMenu("wpAPISubmenu", "wp API Submenu",
    wpAPI_PERMISSIONS::MANAGE_OPTIONS,
    new wpAPI_VIEW(wpAPI_VIEW::CONTENT, "In line html for submenu page", []));


$post_page = $wp_api->CreatePostType("wpAPI_custom_post_type", "wpAPI Custom Post Type");

$post_page->AddField(new Text("_email", "Your Email", new ElementPermission()));
$post_page->AddField(new Text("_firstName", "Last Name", new ElementPermission()));


$menu_base->AddChild($sub_menu);

$menu_base->AddChild($post_page);


$menu_base->Render();
```

The above will create the following, configuring all items properly in the background. i.e fields, saving and updating etc

New Post Type Page
![alt text](https://github.com/walisc/wpAPI/blob/master/Docs/images/wpAPI_view.jpg "New Post Type Page")

Adding New Post Type
![alt text](https://github.com/walisc/wpAPI/blob/master/Docs/images/wpAPI_addnew.jpg "Add New Post Type")
