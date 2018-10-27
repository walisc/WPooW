---
id: custom_elements-create
title: Creating Custom Element
---

Sometimes there are situations when you might need to create custom elements that utilise the WPooW elements' features. To
do this all you need to do is extend the WPooW `BaseElement` class and override the methods

* ReadView
* EditView
* ProcessPostData

These methods are highlighted below in more detail:-

## Methods to Override

* ### ReadView($post)

    ```yaml
    Description:
         Method called when rendering the ReadView of the element. This method should return a
         html string. The current value of the element can be accessed through the
         GetDatabaseValue method (highlighted below)


    Parameters:
        $post:
            Type: PostType
            Description: The PostType associated with the current element when being viewed

    Returns: String
    ```

    ```php
    // Example
    function ReadView($post){
          echo sprintf('<p>%s</p>',$this->GetDatabaseValue($post));
    }
     ```

* ### EditView($post)

    ```yaml
    Description:
         Method called when rendering the EditView of the element. This method should return
         a html string, of which this html string must have an input dom element with 
         a 'name' attribute that is the same as the id of the element being render (i.e $this->id).
         This is used to process data correctly when a POST request is made (see example below)0.

         Another important thing to note is to call the parents EditView method first, as this 
         generates the wp_nonce required for the elements input field.

         The current value of the element can also be accessed by using the GetDatabaseValue
         method.

    Parameters:
        $post:
            Type: PostType
            Description: The Post Type associate with the current element when being viewed

    Returns: String
    ```

     ```php
    // Example
    function EditView($post){
      parent::EditView($post)
      echo sprintf('<input type="text" name="%s" value="%s"/>',
                      $this->id,
                      $this->$this->GetDatabaseValue($post));
    }
     ```

* ### ProcessPostData($post_id)

    ```yaml
    Description:
         This method is called when saving the data for the element. Override this method 
         and call the 'SaveElementData' method after obtaining the appropriate data
         for the element to persist the data (see example below).

         Note - it is important to call the parent's ProcessPostData method first before continuing
                with operations as this ensures that the data being saved has been sanitised
                correctly (although you might still need to do so again for your specific element
                type)


    Parameters:
        $post:
            Type: PostType ID
            Description: The ID of the PostType being saved

    Returns: String
    ```

    ```php
    // Example
    function ProcessPostData($post_id)
    {
        parent::ProcessPostData($post_id);
        $data = sanitize_text_field($_POST[$this->id]);

        $this->SaveElementData($post_id, $data);
    }
    ```
