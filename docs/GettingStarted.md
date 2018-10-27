---
id: GettingStarted
title: Getting Started
---

As previously mentioned this project is not a WordPress plugin, but rather a library.
To use it you need to either clone or download it from github (https://github.com/walisc/wpAPI)

Once downloaded place the wpAPI folder anywhere in your theme or plugin you are working on.

```bash
.
├── index.php
├── plugins
│   ├── akismet
│   ├── hello.php
│   ├── index.php
│   └── wpoowbookreviewer
│       ├── index.php
│       ├── wpAPI
│       └── wpoowbookreviewer.php
├── themes
│   ├── index.php
│   ├── twentyfifteen
│   └── twentyseventeen
│       ├── functions.php
│       └── wpAPI
└── uploads
```

Once moved, you can `include` the wpAPI.php file in your main class. ([plugin_name].php for plugins and functions.php for themes). Once included you can create a wpAPI instance, which you can then use to interact with the library.

```php
include 'wpAPI/wpAPI.php';

class WpOOWBookReviewer{

    private $wpOOW;

    function __construct()
    {
        $this->wpOOW =  new wpAPI();
        $this->CreateBookReviewPostType();
    }

    function CreateBookReviewPostType(){
        $bookReviewPostType = $this->wpOOW->CreatePostType("_bookReview", "Book Review", true);

        $bookReviewPostType->AddField(new Text("_bookTitle", "Book Title"));
        $bookReviewPostType->AddField(new Text("_bookAuthor", "Book Author"));
        $bookReviewPostType->AddField(new Uploader("_bookImage", "Book Image"));
        $bookReviewPostType->AddField(new MultiSelect("_bookCategories", "Categories", ["Philosophy" => "Philosophy", "Auto-Biography" => "Auto-Biography", "Fiction" => "Fiction"]));
        $bookReviewPostType->AddField(new RichTextArea("_mySummary", "My Summary"));
        $bookReviewPostType->AddField(new Text("_myRating", "My Rating"));

        $bookReviewPostType->Render();
    }

    //WordPress hooks
    function OnActivate(){
        //$this->CreateBookReviewPostType();
    }

    function OnDeactivate(){
        //TODO: Implement
    }

     function OnUninstall(){
        //TODO: Implement
    }

}
$wpOOWBookReviewer = new WpOOWBookReviewer();
register_activation_hook(__FILE__, [$wpOOWBookReviewer, 'OnActivate']);
register_deactivation_hook(__FILE__, [$wpOOWBookReviewer, 'OnDeactivate']);
```

**Note:-** for the example above I used Object Oriented Programing (OOP) to create the plugin. This is because we are going to work more on this example and OOP helps organise code better. You could have however, created the custom post directly by copying the code in the Introduction section as is.
