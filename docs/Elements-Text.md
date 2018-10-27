---
id: elements-text
title: Text
---

Text element for capturing text data.

| Read View     | Edit View     |
| ------------- | ------------- |
| ![text_read](/images/elements/text_read.png)    |  ![text_edit](/images/elements/text_edit.png) |

Constructor

```php
/**
 * @param $id
 * @param string $label - the label of the element
 * @param array $permissions - View, edit, read rights of the elemenet.
                               See WPooWPermissions in the Overview section.
 *
**/

function __construct($id, $label, $permissions=[])

```

Usage example

```php
// Declaring
$bookTitle = new Text("_bookTitle", "Book Title")
$bookReviewPostType->AddField($bookTitle);


// Fetching Data
foreach ($bookReviewPostType->Query()->Select()->Fetch() as $row)
{
  echo $row["_bookTitle"]
}
```