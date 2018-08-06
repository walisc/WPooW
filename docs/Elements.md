---
id: elements-introduction
title: Overview
---

Elements (or Field Elements) are components used to capture and display data. wpOOW has a few elements you can use in  conjunction with Page Types.

To add a field element to a Post Type page use the `AddField` method of the declared post type

Each element extends the wpOOW Base Elements class. This means for each declared element a minimum of the id parameter is required. Each element type can however specify it own parameter requirements (see field specific requirements below)

Furthermore you can create your own element types by extending the wpOOW base element.

Below is the base constructor, which can be overridden by other elements as need be

```php

/**
 * Constructor for the a base element
 *
 * BaseElement constructor.
 * @param $id
 * @param string $label - the label of the element
 * @param array $permissions - View, edit, read rights of the elemenet. See wpOOWPermissions below
 * @param string $elementPath - the root of the element static files. Defaults to the
                                directory of the file
 * @param array $elementCssClasses - css classes to add to the element
 */
function __construct($id, $label="", $permissions=[], $elementPath = '', $elementCssClasses=[])

```

## wpOOWPermissions

When using wpOOW elements you can pass a permission array which can be used to specify whether
 a viewable or editable for a given viewstate for a Page Type (Add, Edit, View). For instance
 you might require the id field to be invisible when adding a Post Type record, but visible
 when viewing.

For this you need to pass an array specifying a create, read, update string (e.g "cr" for
create and read or "cru" for create, read and update) for a given viewstate. <br><br>

### wpOOW ViewStates

As already mentioned viewstate refer to the WordPress viewstates, but the wpOOW has wrapper state
you can use. These are

* AddPage - maps to the create new entry page of a wpOOW Post Type page
* EditPage - maps to the end entry page of a wpOOW Post Type page
* ViewPage - maps to the view entry page a wpOOW Post Type page <br><br>


### Examples

For the example above we would pass an array like this when we create the element

```php

$permission = [
                wpAPIPermissions::AddPage => "",
                wpAPIPermissions::EditPage => "r"
              ]

$testElement = new Text("_bookID", "Book ID", $permission)
```

Since each user can be mapped to a WordPress permission, we can map these users permission, to
element permission allowing elements to be viewable or editable to certain users for certain conditions.
For instance if we wanted someone with `manage_options` capabilities to be able to modify the id if need be, we could
have something like

```php

$user = wp_get_current_user();

$permission = [
                wpAPIPermissions::AddPage => "",
                wpAPIPermissions::EditPage =>
                    in_array( 'manage_options', (array) $user->allcaps ) ? "ru" : "r"
              ]

$testElement = new Text("_bookID", "Book ID", $permission)
```

**Note:-**

wpOOW does not actual query the viewstate of the current page at render. It rather updates a global variable,
`$CURRENT_VIEW_STATE` with the viewstate, and then uses that. This is useful when dealing with wpOOW Pages
that are not Post Types, when you might need to set this directly for a given condition