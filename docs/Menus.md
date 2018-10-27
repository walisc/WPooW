---
id: menus
title: Menus
---

When creating a plugin or a theme the chances of only having one menu item is rare.
In most cases you will have a parent menu item which consist of other sub menus for
 configuring your plugin/theme.

The Menu interface allows you to create a parent menu item and then append PageTypes to it
as sub menus.

Building on our example from earlier, let's say you want to have another PostType to
give summaries of authors and their books. Using similar code to the one in the Getting Started section we can have something like:-

```php

function __construct()
{
    ...
    $this->CreateAuthorSummariesPostPage();
}

function CreateAuthorSummariesPostPage(){
    $authorSummariesPostType = $this->wpOOW->CreatePostType("_authorSummaries", "Authors Summary", true);

    $authorSummariesPostType->AddField(new Text("_authorName", "Book Title"));
    $authorSummariesPostType->AddField(new Text("_authorBooks", "Author Books"));
    $authorSummariesPostType->AddField(new MultiSelect("_bookCategories", "Categories", ["Philosophy" => "Philosophy", "Auto-Biography" => "Auto-Biography", "Fiction" => "Fiction"]));
    $authorSummariesPostType->AddField(new RichTextArea("_mySummary", "My Summary"));
    $authorSummariesPostType->AddField(new Text("_myRating", "My Rating"));

    $authorSummariesPostType->Render();
}

```


This would however create two menu items, and a user would find it difficult to distinguish
which menu item belongs to your plugin/theme.

![book_author_summary_image](/images/menu_book_author_summary_image.png)

Using the Menu interface we could group these two items under one Menu for which will have
our plugin name. An example of this is below:-

```php

...

 function __construct()
    {
        $this->wpOOW =  new wpAPI();

        $bookReviewPlugin = $this->wpOOW->CreateMenu("_bookReviewPlugin", "wpOOP Book Review");

        $bookReviewPage = $this->CreateBookReviewPostType();
        $authorSummaryPage = $this->CreateAuthorSummariesPostPage();

        $bookReviewPlugin->AddChild($bookReviewPage);
        $bookReviewPlugin->AddChild($authorSummaryPage);

        $bookReviewPlugin->Render();
    }

    function CreateAuthorSummariesPostPage(){
        $authorSummariesPostType = $this->wpOOW->CreatePostType("_authorSummaries", "Authors Summary", true);

        $authorSummariesPostType->AddField(new Text("_authorName", "Book Title"));
        $authorSummariesPostType->AddField(new Text("_authorBooks", "Author Books"));
        $authorSummariesPostType->AddField(new MultiSelect("_bookCategories", "Categories", ["Philosophy" => "Philosophy", "Auto-Biography" => "Auto-Biography", "Fiction" => "Fiction"]));
        $authorSummariesPostType->AddField(new RichTextArea("_mySummary", "My Summary"));
        $authorSummariesPostType->AddField(new Text("_myRating", "My Rating"));

        return $authorSummariesPostType;
    }
    function CreateBookReviewPostType(){
        $bookReviewPostType = $this->wpOOW->CreatePostType("_bookReview", "Book Review", true);

        $bookReviewPostType->AddField(new Text("_bookTitle", "Book Title"));
        $bookReviewPostType->AddField(new Text("_bookAuthor", "Book Author"));
        $bookReviewPostType->AddField(new Uploader("_bookImage", "Book Image"));
        $bookReviewPostType->AddField(new MultiSelect("_bookCategories", "Categories", ["Philosophy" => "Philosophy", "Auto-Biography" => "Auto-Biography", "Fiction" => "Fiction"]));
        $bookReviewPostType->AddField(new RichTextArea("_mySummary", "My Summary"));
        $bookReviewPostType->AddField(new Text("_myRating", "My Rating"));

        return $bookReviewPostType;
    }

...
```

This would produce a menu item like:-

![book_author_summary_combined_image](/images/menu_combined.png)

Creating the menu item is done through the `CreateMenu` WPooW API method. The definition of this is below

```php

/**
 * Create a new menu option that can be added to the wp-admin menu.
 *
 * @param $page_slug - which will be the id of the menu item
 * @param $menu_title - the title of the menu item
 * @param $capability - WordPress capabilities required to access this menu item
 * @param $display_path - uses wpAPI_VIEW. can link to a jinja template or render content passed to it
                          as html. This Page will be shown as the root of the menu item (i.e when you
                          click the menu item to expand it). This will be deprecated soon. To be replaced
                          with WPooW Static Pages
 * @param string $icon - this icon of the menu item
 * @param null $position - the position of the menu item
 *
 * @return Menu
 */
 public function CreateMenu($page_slug, $menu_title, $capability=WP_PERMISSIONS::MANAGE_OPTIONS, $display_path=null, $icon='', $position=null)

```

