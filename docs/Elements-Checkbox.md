---
id: elements-checkbox
title: Checkbox
---

Checkbox element for boolean items

| Read View     | Edit View     |
| ------------- | ------------- |
| ![checkbox_read](/images/elements/checkbox_read.png)    |  ![checkbox_edit](/images/elements/checkbox_edit.png) |

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
$bookAvailableCheckbox = new Checkbox("_isbookAvailable", "Book Available")
$bookReviewPostType->AddField($bookAvailableCheckbox);


// Fetching Data
foreach ($bookReviewPostType->Query()->Select()->Fetch() as $row)
{
    if ($row["_isbookAvailable"] == "on")
    {
        //Do something.
    }
}
```