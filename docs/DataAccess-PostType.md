---
id: da-posttypes
title: Post Type
---

Accessing data saved through a PostType Page is done through the `Query` method of the created PostType page. This returns a object of type WPooWQueryObject,
which uses a linq type syntax to fetch data. The `Fetch` method is the last method called, which executes the query and returns the data.

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
         Used to specify the columns you want returned. Returns all columns if nothing is specified

    Parameters:
        $column:
            Type: array
            Description: Array of PostType field/column names to return

    Returns: WPooWQueryObject
    ```

* ### OrderBy($fieldname, $asc_desc, $use_numbers=false)

    ```yaml
    Description:
        Used to specify the field to order by

    Parameters:
        $fieldname:
            Type: string
            Description: PostType field name you want to order by
        $asc_desc:
            Type: string
            Description: ASC for ascending order based on the fieldname and DESC for descending
            order based on the fieldname
        $fieldname:
            Type: boolean
            Description: if you want to treat the value from the column as a number when ordering,
            else treat as a string

    Returns: WPooWQueryObject
    ```

* ### Fetch()

    ```yaml
    Description:
         Executes the generated wp_query and yields the result

    Returns: Generator
    ```