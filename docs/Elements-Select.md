---
id: elements-select
title: Select
---

Select element, for selecting a single value from a list.

| Read View     | Edit View     |
| ------------- | ------------- |
| ![select_read](/images/elements/select_read.png)    |  ![select_edit](/images/elements/select_edit.png) |

Constructor

```php
 /**
 * @param $id - See BaseElement definitions
 * @param string $label - See BaseElement definitions
 * @param array $options - A value => label array. Eg [value1 => label1, value2 => label2]
 */
function __construct($id, $label, $options, $permissions=[])
```

Usage example

```php
// Declaring
$availableBookAuthors = [
"Nelson_Mandela" => "Nelson Mandela",
"Suzanne_Collins" => "Suzanne Collins",
"Timothy_Keller" => "Timothy Keller"
]
$bookAuthors = new Select("_bookAuthors", "Book Authors", $availableBookAuthors);
$bookReviewPostType->AddField($bookAuthors);


// Fetching Data
foreach ($bookReviewPostType->Query()->Select()->Fetch() as $row)
{
  echo $row["_bookAuthors"]
}
```
