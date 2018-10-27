---
id: elements-uploader
title: Uploader
---

Uploader element. This element can be used to store media items such as pictures. The upload element uses
WordPress's Media Library to upload media and store it appropriately.

The data saved using the Uploader element is saved as a json object with the properties `id`, `url`, `filename`.
To read this data you will need to `json_decode` the data, which will turn it to a php array.

| Read View     | Edit View     |
| ------------- | ------------- |
| ![uploader_read](/images/elements/uploader_read.png)    |  ![uploader_edit](/images/elements/uploader_edit.png) |

Constructor

```php
/**
 * @param $id - See BaseElement definitions
 * @param string $label - See BaseElement definitions
 * @param array $permissions - See BaseElement definitions
 * @param string $uploaderTitle - The title of the media picker dialogue box
 * @param string $buttonText - the text on the upload button on the media picker
 * @param string $enableMultiple - Allowing for selecting multipe of items
 */
function __construct($id, $label, $permissions=[], $uploaderTitle = "Select Item to Upload", $buttonText= "Upload", $enableMultiple = "false")
```

Usage example

```php
// Declaring
$bookImage = new Uploader("_bookImage", "Book Image", [], "Please Select a book Image")
$bookReviewPostType->AddField($bookImage);


// Fetching Data
foreach ($bookReviewPostType->Query()->Select()->Fetch() as $row)
{
    $bookImageData = json_decode($row["_bookImage"]);

    echo '<p><img src="'.$bookImageData->url.'" alt="'.$bookImageData->id.'"  > '+$bookImageData->filename+'</p>';
}
```