---
id: elements-muilti_select
title: MultiSelect
---
MultiSelect element for selecting multiple items from a list of values.

| Read View     | Edit View     |
| ------------- | ------------- |
| ![multiselect_read](/images/elements/multiselect_read.png)    |  ![multiselect_edit](/images/elements/multiselect_edit.png) |

Constructor

```php
 /**
 * @param $id - See BaseElement definitions
 * @param string $label - See BaseElement definitions
 * @param array $options - A value => label array. Eg [value1 => label1, value2 => label2]
 * @param array $permissions - View, edit, read rights of the elemenet.
                              See WpooWPermissions in the Overview section.
 */
function __construct($id, $label, $options, $permissions=[])
```

Usage example

```php

// Declaring

$bookcategoryOptions = [
"Philosophy" => "Philosophy",
"Auto-Biography" => "Auto-Biography",
"Fiction" => "Fiction"
]
$bookCategories = new MultiSelect("_bookCategories", "Book Categories", $bookcategoryOptions);
$bookReviewPostType->AddField($bookCategories);


// Fetching Data
foreach ($bookReviewPostType->Query()->Select()->Fetch() as $row)
{
  echo (is_array($book["_bookCategories"]) ? implode(',', $book["_bookCategories"]) : '')
}
```