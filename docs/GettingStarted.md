---
id: GettingStarted
title: Getting Started
---

As previously mentioned this project is not a WordPress plugin, but rather a library.
To use it you need to either clone it or download it from github (https://github.com/walisc/wpAPI)

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

Once moved, you can `include` the wpAPI.php file in your main class. ([plugin_name].php for plugins and functions.php of themes). Once all references have been set  you can create a instance of wpAPI, which you can the use to create *Page Types*.

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

**Note:-** for the example above I used Object Oriented Programing to create the plugin. This is because we are going to work more on this example and will help if the code is well organised. You could have however, created the custom post direct by coping the code in the Introduction section.