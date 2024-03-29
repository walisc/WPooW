# Wordpress Object Oriented Wrapper
#### An OOP Wordpress wrapper for rapid development

If you have had to create a custom theme/plugin in WordPress (which requires quite a bit of configuration), you know this can be quite cumbersome. 
This wrapper aims to simplify this process by providing an object-oriented library which abstracts most of the tasks associated with this.
Below is a simple example showing you how you can easily create a Custom [PostType](https://codex.wordpress.org/Post_Types) using this wrapper.

```php
//functions.php

include 'wpAPI/wpAPI.php';

$WPooW = new wpAPI();
$bookReviewPostType = $WPooW->CreatePostType("_bookReview", "Book Review", true);

$bookReviewPostType->AddField(new Text("_bookTitle", "Book Title"));
$bookReviewPostType->AddField(new Text("_bookAuthor", "Book Author"));
$bookReviewPostType->AddField(new Uploader("_bookImage", "Book Image"));
$bookReviewPostType->AddField(new MultiSelect("_bookCategories", "Categories", ["Philosophy" => "Philosophy", "Auto-Biography" => "Auto-Biography", "Fiction" => "Fiction"]));
$bookReviewPostType->AddField(new RichTextArea("_mySummary", "My Summary"));
$bookReviewPostType->AddField(new Text("_myRating", "My Rating"));

$bookReviewPostType->Render();


```

This will create a custom page (available via wp-admin). See below:-

![intro_images](http://wpoow.devchid.com/images/intro_output_image_input.png)

*Fig1: Grid Layout of new custom type*


![intro_images_expanded](http://wpoow.devchid.com/images/intro_main_image_expanded.png)

*Fig2: Adding new custom type*

To access the data added through the custom PostType, you can use a traditional WordPress query ([`WP_QUERY`](https://codex.wordpress.org/Class_Reference/WP_Query) ) by referencing your declared PostType id  (in the case above, it will be `_bookReview`). WPooW  however, provides a wrapper class which makes it easier to access this data. An example of how you would fetch this data using the WPooW library is below:-

```php+HTML
<style>
	.book_block{
		display: inline-block;
	}
	.book_img{
		float: left;
		width: 50%;
	}
	.book_img  img{
		height: 200px;
		width: auto;
	}

	.book_details {
		float: right;
		width: 45%;
		padding-left: 2%;
	}

	.book_details p {
		font-size: 14px;
		margin-bottom: 2px;
		margin-top: 2px;
		color: white;
	}
</style>

<div class="wrap">
   <?php
      $bookReviews = wpAPIObjects::GetInstance()->GetObject("_bookReview");
      foreach ($bookReviews->Query()->Select()->Fetch() as $book)
      {

         echo '<div class="book_block">';
         echo ' <div class="book_img">';
         echo '     <img src="'.json_decode( $book["_bookImage"])->url.'" alt="'.$book["_bookTitle"].'"  >';
         echo '     </div>';
         echo '     <div  class="book_details">';
         echo "    <p>".$book["_bookTitle"]."</p>";
         echo "    <p>".$book["_bookAuthor"]."</p>";
         echo "    <p>". (is_array($book["_bookCategories"]) ? implode(',', $book["_bookCategories"]) : '')."</p>";
         echo "    <p>".$book["_myRating"]."</p>";
         echo ' </div>';
         echo '</div>';

      }
   ?>

</div>Result
```


Modifying the WordPress TwentySeventeen theme template our web page could look like:- 

![1529530425830](http://wpoow.devchid.com/images/intro_output_image.png)

## Documentation

The WPooW library is fully documented at [http://wpoow.devchid.com](http://wpoow.devchid.com/). If you think of anything else that should be documented that's not there, please do give a shout. 

## Contributing

WPooW is an opensource project and contributions are valued. 

If you are contributing a bug fix, please create a pull request with the following details
* The problem/bug you are addressing 
* The version of WPooW the fix is for 
* How you tested the fix 

If it's a new feature, please add it as a issue with the label enhancement, detailing the new feature and why you think it's needed. Will discuss it there and once it's agreed upon you can create a pull request with the details highlighted above. 

## Authors

* **Chido Warambwa** - *Initial Work* - [devchid.com](http://devchid.com) 
  
## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details

## Notes

This library is designed to be used by developers for creating WordPress themes and plugins. It is not a plugin or a theme in and off itself. 



