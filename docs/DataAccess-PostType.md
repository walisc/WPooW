---
id: da-posttypes
title: Post Type
---

Accessing data saved through a Post Type Page is done through the `Query` method of the Post Type. This returns a object of type wpOOWQueryObject,
which use a linq type syntax to fetch data. The `Fetch` method is the last method called, which executes the query and returns the data.

```php
$bookReviews = wpAPIObjects::GetInstance()->GetObject("_bookReview");
foreach ($bookReviews->Query()->Select()->Fetch() as $book)
{
    //Do Something
}
```

## API

* ### Select($colums = [])

    ```yaml
    Description:
         Used to specify thee columns you want returned. Returns all columns if nothing specified

    Paramters:
        $colums:
            Type: array
            Description: Array of Post Type fieldname

    Returns: wpOOWQueryObject
    ```

* ### OrderBy($fieldname, $asc_desc, $use_numbers=false)

    ```yaml
    Description:
         Added a field element to the Post Type

    Paramters:
        $fieldname:
            Type: string
            Decription: Post Type field name you want to order by
        $asc_desc:
            Type: string
            Description: ASC for ascending order based on the fieldname and DESC for descending
            order based on the fieldname
        $fieldname:
            Type: boolean
            Description: if you want to treat the value from the colum as a number when ordering,
            else treat as a string

    Returns: wpOOWQueryObject
    ```

* ### Fetch()

    ```yaml
    Description:
         Excutes the generated wp_query and yeilds the result

    Returns: Generator
    ```