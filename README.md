[![Run Status](https://api.shippable.com/projects/57e6376ddd566d0f00a7f62f/badge?branch=master)](https://app.shippable.com/github/walisc/wpAPI)
[![Coverage Badge](https://api.shippable.com/projects/57e6376ddd566d0f00a7f62f/coverageBadge?branch=master)](https://app.shippable.com/github/walisc/wpAPI)

# Wordpress Object Oriented Wrapper
#### An OOP Wordpress wrapper for rapid development

If you have ever had to create a custom theme/plugin in Wordpress which requires a lot of backend configuration, this can be tedious task. This wrapper aims to simplify this process by providing a object-oriented way which abstracts most of these tasks. Below is a simple example showing you how you can easily create a custom posttype you can use to store information of books you have read.

```php
//functions.php

include 'wpAPI/wpAPI.php';q

$wpOOW = new wpAPI();
$bookReviewPostType = $wpOOW->CreatePostType("_bookReview", "Book Review", true);

$bookReviewPostType->AddField(new Text("_bookTitle", "Book Title"));
$bookReviewPostType->AddField(new Text("_bookAuthor", "Book Author"));
$bookReviewPostType->AddField(new Uploader("_bookImage", "Book Image"));
$bookReviewPostType->AddField(new MultiSelect("_bookCategories", "Categories", ["Philosophy" => "Philosophy", "Auto-Biography" => "Auto-Biography", "Fiction" => "Fiction"]));
$bookReviewPostType->AddField(new RichTextArea("_mySummary", "My Summary"));
$bookReviewPostType->AddField(new Text("_myRating", "My Rating"));

$bookReviewPostType->Render();


```

This will create a custom posttype page (available at login) that will look like

![1529530655397](https://github.com/walisc/wpAPI/blob/master/static/images/intro_output_image_input.png, "Custom PostType Grid")

to make a plugin

![1528991852815](https://github.com/walisc/wpAPI/blob/master/static/images/intro_main_image_expanded.png, "Custom PostType - New")

To acces the data added through the custim posttype, you can you a tradtion wordpress query `WP_QUERY` by reference you declared posttype id for the posttype property (in the case above it will be `_bookReview`). wpOOW  however provides a wrapper class which makes it easier to access this data. An example  how you would fetch is below

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



This could be used to produce a webpage like: (below:- based on the Wordpress TwentySeventeen template )

![1529530425830](https://github.com/walisc/wpAPI/blob/master/static/images/intro_output_image.png. "Sample HTML")






