---
id: elements-textarea
title: Text Area
---

TextArea element. Similar to the [Text Element](/docs/elements-text.html), with a larger area to capture content.

| Read View     | Edit View     |
| ------------- | ------------- |
| ![textarea_read](/images/elements/textarea_read.png)    |  ![checkbox_edit](/images/elements/textarea_edit.png) |

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
$bookMetaDescription = new TextArea("_bookMetaDescription", "Book Meta Description")
$bookReviewPostType->AddField($bookMetaDescription);


// Fetching Data
foreach ($bookReviewPostType->Query()->Select()->Fetch() as $row)
{
  echo $row["_bookMetaDescription"]
}
```