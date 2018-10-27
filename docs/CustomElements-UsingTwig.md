---
id: custom_elements-using_twig
title: Using Twig for templating
---
For more complex elements you might want to use a html template. WPooW comes embedded with the [twig](https://twig.symfony.com/) template
engine (which is used to render the predefined elements). The template engine can also be used to render your
custom elements.

Using the examples above we could the change the `ReadView` and the `EditView` to the following:-

```php
// Example
function ReadView($post)
{
    echo $this->twigTemplate->render('/read_view.twig',
                                    ["value" => $this->GetDatabaseValue($post)]);
}


function EditView( $post)
{
   parent::EditView($post);
   echo $this->twigTemplate->render('/edit_view.twig', [
       "id" => $this->id,
       "label" => $this->label,
       "value" => $this->GetDatabaseValue($post)
   ]);
}
 ```

 In the examples above we are saying use the template `read_view.twig` / `edit_view.twig` to render this elements
 with the parameters value...etc. The location of the template files is relative to the php file of your
 custom element (unless in the construction of the element you specify a different elementPath See [Elements Overview Above](/docs/elements-introduction.html))
