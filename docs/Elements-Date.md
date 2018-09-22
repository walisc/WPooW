---
id: elements-date
title: Date
---

Date element for date values. The used the browsers standard datetime picker, hence will appear
 slightly different on each browser.

| Read View     | Edit View     |
| ------------- | ------------- |
| ![date_read](/images/elements/date_read.png)    |  ![date_edit](/images/elements/date_edit.png) |

Constructor

```php
/**
 * @param $id
 * @param string $label - the label of the element
 * @param array $permissions - View, edit, read rights of the elemenet.
                               See wpOOWPermissions in the Overview section.
 *
**/

function __construct($id, $label, $permissions=[])

```

Usage example


```php

// Declaring
$dataReviewed = new wpAPIDateTime("_dateReviewed", "Date Reviewed")
$bookReviewPostType->AddField($dataReviewed);


// Fetching Data
foreach ($bookReviewPostType->Query()->Select()->Fetch() as $row)
{
    $dateBookReviewed = new DateTime($row["_dateReviewed"])
    echo  $dateBookReviewed->format('Y-m-d H:i:s')
}
```

**Note:-** for the wpOOW Datetime element we declare it using the class `wpAPIDateTime`. This is because php already has
it own `DateTime` class, which would result in a conflict between the two.