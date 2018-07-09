---
id: pt-posttypes
title: Post Types
---


![intro_page](/images/intro_output_image_input.png)


Post Type pages are in essence [Custom Post Types](https://codex.wordpress.org/Post_Types) in WordPress.
You could create a custom post type in Wordpress directly, but this is a bit cumbersome and slightly
confusing. WpOOW abstracts all the nitty-gritties involved in creating custom post types,
include saving and updating the custom post type created.

To create a PostType Page using the `CreatePostType` method associated with the $wpOOW API. The definition
of this is below

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

This will create a Post Type with the default columns for Custom Post Type. You can specify
your own fields by using the `AddField` method. wpOOW has a few field types you can use (See element types).

The `Render` method is responsible for actually displaying the Custom PostType created.


## API

* ### AddField($aField)

    ```yaml
    Description:
        Added a field element to the Post Type

    Paramters:
        $aField:
            Type: wpOOW/BaseElement
    ```

* ### Query()

    ```yaml
    Description:
         Gets the query object for the Post Type. See Data Access

    Returns: wpOOWQueryObject
    ```


* ### Render()

    ```yaml
    Description:
         Responsible for rendering the Custom Post Type
    ```
* ### RegisterAfterSaveEvent ( $method,  $class = null)

    ```yaml
     Description:
        Class to register methods that are called after saving data for a custom Post Type.
        This is useful if you required to do some additional operation once data saving has
        been confirmed. An example of this could be sending an email.  The method you pass
        in needs to expect an array (which will have the $field => $value pair of the custom
        post type.

    Paramters:
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
        Register a method that is called before a custom post query is run. This is useful
        if you want to modify the query in any way, for instance, change the return order.
        The method you pass in needs to expect a wp_query objet. No need to return the wp_query
        object as it is passed by reference.

    Paramters:
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
        Class to register methods that are called before saving data for a custom Post Type.
        This is useful if you required to do some additional operation before saving the data.
        An example of this is setting a id field.  The method you pass in needs to expect an
        array (which will have the $field => $value pair of the custom post type.

    Paramters:
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









