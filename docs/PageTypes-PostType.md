---
id: pt-posttypes
title: Post Types
---


![intro_page](/images/intro_output_image_input.png)


Post Type pages are in essence [Custom Post Types](https://codex.wordpress.org/Post_Types) in WordPress.
You could create a custom post type in Wordpress directly, but this is a bit cumbersome and slightly
confusing. WPooW abstracts all the nitty-gritties involved in creating custom post types,
including saving and updating the custom post type created.

To create a PostType Page use the `CreatePostType` method associated with the WPooW library. The definition
of this is below:-

```php
/**
 *
 * @param $page_slug - page slug to be used for the custom post type
 * @param $title - page title to be used for the custom post type
 * @param bool $persist - should the custom page type be persisted for use in
   rendering frontend content
 * @return PostType - extends default wordpress post type options
 */
public function CreatePostType($page_slug, $title, $persist=false, $options=[])

```

Usage example below

```php
$bookReviewPostType = $wpOOW->CreatePostType("_bookReview", "Book Review", true);
```

This will create a Custom PostType with the default columns set. You can specify
your own fields/add columns, by using the `AddField` method. WPooW has a few field types you can use (See element types below).

The `Render` method is responsible for actually displaying the Custom PostType created.


## API

* ### AddField($aField)

    ```yaml
    Description:
        Add a field element to the PostType

    Parameters:
        $aField:
            Type: WPooW/BaseElement
    ```

* ### Query()

    ```yaml
    Description:
         Gets the query object for the PostType. See Data Access below

    Returns: WPooWQueryObject
    ```


* ### Render()

    ```yaml
    Description:
         Responsible for rendering the Custom PostType
    ```
* ### RegisterAfterSaveEvent ( $method,  $class = null)

    ```yaml
     Description:
        Used to register methods that are called after saving data for a Custom PostType.
        This is useful if you required to do some additional operations once your data is saved. 
        An example of this could be sending an email.  The method you pass
        in needs to expect an array (which will have the $field => $value pair of the Custom
        PostType's columns.

    Parameters:
        $method:
            Type: string
            Description: Method to call in the class or global scope
        $class:
            Type: class
            Description: Class the method belongs to. Can be left null if using global scope
    ```

    ```php
    // Example
    $bookReviewPostType->RegisterAfterSaveEvent("SendNewBookEmail");

    function SendNewBookEmail($data)
    {
        $subject = sprintf("New book review: %s", $data["_bookTitle"])
        $emailMessage = "Hello there, I have a new book I've reviewed. \
        Check out my blog to have a look:)"
        wp_mail("recipient@example.com", sprintf($subject, $emailMessage);
    }
    ```

* ### RegisterBeforeDataFetch ( $method,  $class = null)

    ```yaml
    Description:
        Used to register a method that is called before a Custom PostType query is run. This is useful
        if you want to modify the query in any way, for instance, change the return order.
        The method you pass in needs to expect a wp_query object. No need to return the wp_query
        object as it is passed by reference.

    Parameters:
        $method:
            Type: string
            Description: Method to call in the class or global scope
        $class:
             Type: class
             Description: Class the method belongs to. Can be left null if using global scope


    ```

    ```php
    // Example
    $bookReviewPostType->RegisterBeforeDataFetch("OrderBooksByRatings");

    function OrderBooksByRatings($query){
       $query->set('order', 'DSC');
       $query->set('orderby', 'meta_value_num');
       $query->set('meta_key', '_bookreview__ratings');
    }
    ```

* ### RegisterBeforeSaveEvent ( $method,  $class = null)

    ```yaml
    Description:
        Used to register methods that are called before saving data for a custom PostType.
        This is useful if you required to do some additional operations before saving the data.
        An example of this is setting a id field.  The method you pass in needs to expect an
        array (which will have the $field => $value pair of the custom PostType's columns.

    Parameters:
        $method:
            Type: string
            Description: Method to call in the class or global scope
        $class:
             Type: class
             Description: Class the method belongs to. Can be left null if using global scope
    ```

    ```php
    //Example
    $bookReviewPostType->RegisterBeforeSaveEvent("CreateBookID");

    function CreateBookID($data)
    {
        $data["_bookId"] = sanitize_title($data[sprintf("%s_%s", "_bookReview", "_bookTitle")]);
        return $data;
    }
    ```









