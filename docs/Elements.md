---
id: elements-introduction
title: Overview
---

Elements (or Field Elements) are components used to capture and display data. WPooW has a few elements you can use in conjunction with PageTypes to this end.

To add a field element to a PostType page use the `AddField` method of the declared PostType.

Each element extends the WPooW Base Elements class. This means each declared element must at least pass in an id parameter during construction. Each element type can however specify it's own required parameters as well (see field specific requirements below).

Furthermore you can create your own element types by extending the WPooW Base Element class.

Below is the base constructor, which can be overridden by other elements as need be.

```php

/**
 * Constructor for the Base Element
 *
 * BaseElement constructor.
 * @param $id
 * @param string $label - the label of the element
 * @param array $permissions - View, edit, read rights of the elemenet. See WPooWPermissions below
 * @param string $elementPath - the root of the element static files. Defaults to the
                                directory of the file
 * @param array $elementCssClasses - css classes to add to the element
 */
function __construct($id, $label="", $permissions=[], $elementPath = '', $elementCssClasses=[])

```

## WpooWPermissions

When using WPooW elements you can pass in a permissions array which can be used to specify whether
 an element is viewable or editable for a given viewstate for a PageType (Add, Edit, View). For instance
 you might require the id field to be hidden when adding a PostType record, but visible
 when viewing.

For this you need to pass an array specifying a create, read, update string (e.g "cr" for
create and read or "cru" for create, read and update) for a given viewstate (see example below). <br><br>

### WpooW ViewStates

As already mentioned viewstate refer to the WordPress viewstates, of which WPooW has wrapper methods for accessing these states. These are

* AddPage - maps to the create new record page of a WPooW PostType page
* EditPage - maps to the edit record page of a WPooW PostType page
* ViewPage - maps to the view record page a WPooW PostType page <br><br>


### Examples

For the example above (making the id field hidden when adding a new record and read only when editing) we would pass an array like this when we create the element:-

```php

$permission = [
                wpAPIPermissions::AddPage => "",
                wpAPIPermissions::EditPage => "r"
              ]

$testElement = new Text("_bookID", "Book ID", $permission)
```

Since each user can be mapped to a WordPress permission, we can map these users permission, to
element permissions allowing elements to be viewable or editable to certain users for certain conditions.
For instance if we wanted someone with `manage_options` capabilities to be able to modify the id if need be, we could
have something like:-

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

WPooW does not actually query the viewstate of the current page at render. It rather updates a global variable,
`$CURRENT_VIEW_STATE` with the viewstate, and then uses that. This is useful when dealing with WPooW Pages
that are not PostTypes, when you might need to set this directly for a given condition.