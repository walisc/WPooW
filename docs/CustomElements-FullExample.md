---
id: custom_elements-full_example
title: Full Example
---
Below is an example in which we replace the rating text element in our example with a slider.

```php
class CustomRatingSelectorSimple extends BaseElement{

    function BaseScriptsToLoad( )
    {
        $element_uri_path = wpAPIUtilities::GetWpAPUriLocation(dirname(__FILE__)). "/";

        $this->EnqueueElementBaseScript("jquery_ui", $element_uri_path  . "libs/jquery-ui.min.js",[], ["jquery"], "1.0.0", true);
        $this->EnqueueElementBaseCSS("jquery_ui_css", $element_uri_path . "libs/jquery-ui.min.css", [], "1.0.0", $media = 'all');
        $this->EnqueueElementBaseCSS("rating_selector_simple_css", $element_uri_path . "CustomRatingSelector.css", [], "1.0.0", $media = 'all');
    }

    function ReadView($post)
    {
        echo $this->twigTemplate->render("/read_view.twig", [
        "element_id" => $this->id,
        "value" => $this->GetDatabaseValue($post)
    ]);
    }

    function EditView($post)
    {
        parent::EditView($post);

        $this->EnqueueElementScript("/CustomRatingSelector.element.js", ["element_id" => $this->id]);

        echo $this->twigTemplate->render("/edit_view.twig", [
            "element_id" => $this->id,
            "value" => $this->GetDatabaseValue($post)
        ]);

    }

    function ProcessPostData($post_id)
    {
        parent::ProcessPostData($post_id);
        $data = sanitize_text_field($_POST[$this->id]);

        $this->SaveElementData($post_id, $data);

    }


}
```

As can be seen from the example above we are using the jquery-ui slider component. This means we are going to have to download
these files and add them to the components folder. There is also an additional css file we will use to style our slider. The content
of that css file is below:-

```css
    .rating_selector_simple {
        width:70%; margin:25px
    }

    .custom-handle_simple {
        width: 3em;
        height: 1.6em;
        top: 50%;
        text-align: center;
        line-height: 1.6em;
    }
```

As one would expect when using a jquery library, there is need to usually initialize some javascript somewhere.
For this component we will need to initialize the javascript for both the read_view and edit_view. The javascript we will use
is highlighted below:-

```js
( function() {
    $( "#{{ element_id }}_edit_view" ).each(function(){
        var value = $(this).children("div").text()

        //set the hidden input value. This input value is what is sent back when a post occurs
        $("#{{element_id}}_value").val(value)

        // Initialize the slider
        $(this).slider({
            value: value,
            slide: function( event, ui ) {
                $("#{{element_id}}_value").val(ui.value)
                $(this).children("div").text( ui.value );
            }
        });

    })

    $( "#{{ element_id }}_read_view" ).each(function(){
        $(this).slider({
            value: $(this).children("div").text(),
            disabled: true
        });

    })
})();
```

Lastly we need to create our read_view and edit_view templates. These are highlighted below:-

```twig
<!-- Read View -->
<div id="{{ element_id }}_read_view" class="rating_selector_simple">
    <div class="ui-slider-handle custom-handle_simple">{{ value }}</div>
</div>

```

```twig
<!-- Edit View -->
<div id="{{ element_id }}_edit_view" class="rating_selector_simple">
    <div class="ui-slider-handle custom-handle_simple">{{ value }}</div>
</div>
<input type="hidden" id="{{element_id}}_value" name="{{element_id}}" value="" />

```

And that's it! I added this at the root of my plugin (but you can theoretically add it anywhere) resulting in a file
structure that looks like this:-

```bash
wpoowbookreviewer
├── CustomRatingSelectorSimple
│   ├── CustomRatingSelector.css
│   ├── CustomRatingSelector.element.js
│   ├── CustomRatingSelectorSimple.php
│   ├── edit_view.twig
│   ├── libs
│   │   ├── jquery-ui.min.css
│   │   └── jquery-ui.min.js
│   └── read_view.twig
├── index.php
├── wpAPI
└── wpoowbookreviewer.php
```

All we need to do now is use our custom component in creating our PostType. To do this we replace the `_myRating` element
creation with our new component (after including it).

```php

include 'customelements/CustomRatingSelectorSimple/CustomRatingSelectorSimple.php';
...
function CreateBookReviewPostType(){
    ...
    $bookReviewPostType->AddField(new CustomRatingSelectorSimple("_myRating", "My Rating"));

    return $bookReviewPostType;
}

```

The final element looks something like this:-

| Edit View     | Read View     |
| ------------- | ------------- |
| ![custom_slider_read](/images/slider_example_edit.png)    |  ![custom_slider_edit](/images/slider_example_view.png) |

You can download the source code for this example [here](https://github.com/walisc/wpAPI/tree/master/docs/customelements/CustomRatingSelectorSimple). If you want to see a more complex example, in which the rating
 is aggregated from different categories (see sample picture below), you can find that [here](https://github.com/walisc/wpAPI/tree/master/docs/customelements/CustomRatingSelector).


![custom_slider_complex](/images/slider_complex_example.png)