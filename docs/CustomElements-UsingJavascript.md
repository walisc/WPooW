---
id: custom_elements-using_javascript
title: Using Javascript (and CSS)
---
Sometimes you need to create elements that use javascript. Such is the case if you are using some compound/interactive
UI. When you element uses javascript you need to use the `EnqueueElementBaseScript` and the `EnqueueElementScript`
methods.

The `EnqueueElementBaseScript` method is used to enqueue scripts that will be used by all instance of your element
 type (more like a shared library). **Note:-** This method should only be used in the `BaseScriptsToLoad` method, which
 you will have to overwrite. If you don't you will get a `doing_it_wrong` WordPress error. And example of this is
 below:-

 ```php
 //Example

 function BaseScriptsToLoad()
 {
     $this->EnqueueElementBaseScript("my_slider_element",
                                       $this->GetElementURIDirectory()  . "slider.js",
                                       [], ["jquery"], "1.0.0", true);

 }
 ```

 `EnqueueElementScript` is used to enqueue javascript for a particular instance of your custom element type, for
 instance if you will require to bind to your custom element id. This method should be used with the `ReadView` method
 or the `EditView` method.


 ```php
 function EditView( $post)
 {
     parent::EditView($post);

     $this->EnqueueElementScript('/my_slider_element.element.js',  ["id" =>$this->id,
                                                                    "title" => $this->uploaderTitle]);

     ....
 }

 ```

**Important Notes:-**

* When you custom element use javascript to get the parsed value, you should set this value to a hidden input field
in you read/edit view that has the name set to you element id. This will allow wpOOW to pick use the value correctly
when a post is made
* All javascript that are enqueued using the the `EnqueueElementBaseScript` or `EnqueueElementScript` go through the
twig template engine. This means you can use twig template placeholders in your javascript. This is useful for passing
value from you code

## JS enqueuing methods

```php

 /**
 * Used to add base script for the element. This script will be shared by all instances for this type.
 * Only call this withing the BaseScriptsToLoad method as you will get `doing_it_wrong` word press error
 *
 * @param $handle - name used by WordPress to recognise this script
 * @param $src - location of the file
 * @param array $shared_variable - dictionary of variable to use in your javascript file using twig templating placeholders
 * @param array $deps - javascript dependancies
 * @param bool $ver - file version, for browser caching
 * @param bool $in_footer - add to the footer or header of html
 */
protected function EnqueueElementBaseScript($handle, $src, $shared_variable = [],
                                            $deps = [], $ver = false, $in_footer = false )

```

```php
/**
 * Used to and a instance specific script. The script will be added for each instance of the element.
 * Also not this script will be added inline
 *
 * @param $src_path - location of the file
 * @param array $shared_variables - dictionary of variable to use in your javascript file using twig templating placeholders
 * @param null $handler - name used by WordPress to recognise this script
 */
protected function EnqueueElementScript($src, $shared_variables= [], $handler=null)
```

# Using CSS in your custom element

Similarly to enqueuing a javascript file for your element you can also enqueue a css file. The process is exactly
this same as that of enqueuing a javascript, with the caveat that you use the `EnqueueElementBaseCSS` method instead.
Similar to the EnqueueElementBaseScript method you should only call this method within the `BaseScriptsToLoad` method.

```php
function BaseScriptsToLoad()
{
    $pluginURLPath = wpAPIUtilities::GetWpAPUriLocation(__DIR__);

    $this->EnqueueElementBaseCSS("my_slider_element_css",
                                  sprintf("%s%s%s", $this->GetElementURIDirectory()  . "slider.css",  [], []);

}
```

## CSS enqueuing methods

```php

/**
 * Used to add base css for the element. The css will be shared by all instances for this type.
 * Only call this withing the BaseScriptsToLoad method as you will get `doing_it_wrong` word press error
 *
 * @param $handle  - name used by WordPress to recognise this script
 * @param $src - location of the file
 * @param array $deps - css dependancies
 * @param bool $ver - file version, for browser caching
 * @param string $media - what type of media it applies too
 */
protected function EnqueueElementBaseCSS($handle, $src, $deps = array(), $ver = false, $media = 'all' )

```