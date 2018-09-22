---
id: custom_elements-create
title: Creating Custom Element
---

Sometimes there are situation when you might need to create custom elements that utilise the wpOOW elements features. To
do this all you need to do is extend the wpOOW `BaseElement` class and override the methods

* ReadView
* EditView
* ProcessPostData

This method definitions of each of these methods are highlighted below

## Methods to Override

* ### ReadView($post)

    ```yaml
    Description:
         Method called when rendering the ReadView of the element. This method should return a
         html string. The curent value of the element can be accessed by using the
         GetDatabaseValue method (highlighted below)


    Paramters:
        $post:
            Type: PostType
            Description: The Post Type associate with the current element when being viewed

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
         a html string, of which the html element must have a 'name' attribute for
         its input type which is the same id of the element being render (i.e $this->id).
         This is used to process data correct when a POST request is made.

         Another important note it to call the parents EditView method, as this generates the
         wp_nonce required for the elements input field.

         The curent value of the element can also be accessed by using the GetDatabaseValue
         method.

    Paramters:
        $post:
            Type: PostType
            Description: The Post Type associate with the current element when being viewed

    Returns: String
    ```

     ```php
    // Example
    function EditView($post){
      parent::EditView($post)
      echo sprintf('<input type="text" id="%s" name="%s" value="%s"/>',
                      $this->id,
                      $this->id,
                      $this->$this->GetDatabaseValue($post));
    }
     ```

* ### ProcessPostData($post_id)

    ```yaml
    Description:
         This method is called when we about to save the data for the element. Override this
         method to get the correct data to save through the 'SaveElementData' method (see below)

         Note - it is important to call the parent ProcessPostData method first before continuing
                with operations as this ensure the data being returned has been santised
                correctly (although you might still need to do again for your specific element
                type)


    Paramters:
        $post:
            Type: PostType ID
            Description: The ID of the Post Type being saved

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

//Once you have created you element you can simple include
Note
twig Elements
javascript
full example