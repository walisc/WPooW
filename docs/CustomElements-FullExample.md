---
id: custom_elements-full_example
title: Full Example
---

Let's say for our book review rating we want to replace with a aggregated score consisting of 3 categories (i.e obtaining an average of the 3 scores)
  * Intere
  * Inetr
  * Interes

We could do this by using text input types (and the calculate the aggregate `onchange`), but we also want to make
the UI nice and easier for the user, hence we are going to use sliders. The UI we are hoping for at the end will be
something similar to:-


[pic]

To do this, first we need to create a custom folder for our element, `CustomRatingsSelector`. In this folder we are going to have our
read_view.twig, edit_view.twig and the file CustomRatingsSelector.php. We are going to use jquery-ui slider, so we are also going to add the
jquery-ui.min.js in here. In addition to the we are going to have two extra file custom_ratings_selector.element.js (for
element specific javascript) and custom_ratings_selector.css (for the element styling). The file structure can be seen below.

First we are going to start with the CustomRatingsSelector.php