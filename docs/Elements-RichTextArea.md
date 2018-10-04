---
id: elements-richtextarea
title: RichText Area
---

Textbox element for RichText content. This uses WordPress's wp_editor[L], which in turn uses Tinymce editor

| Read View     | Edit View     |
| ------------- | ------------- |
| ![richtext_area_read](/images/elements/richtext_area_read.png)    |  ![richtext_area_edit](/images/elements/richtext_area_edit.png) |

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
$mySummaryDetails = new Checkbox("_mySummary", "My Summary")
$bookReviewPostType->AddField($mySummaryDetails);


// Fetching Data

foreach ($bookReviewPostType->Query()->Select()->Fetch() as $row)
{
    echo html_entity_decode(wpautop($row["_mySummary"]));
}
```

Note:- [wpautop](https://codex.wordpress.org/Function_Reference/wpautop) is a WordPress functions which changes double line-breaks in the text into HTML paragraphs (```<p>...</p>```).
[html_entity_decode](http://php.net/manual/en/function.html-entity-decode.php) convert all HTML entities to their applicable characters.