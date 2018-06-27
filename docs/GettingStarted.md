---
id: GettingStarted
title: Getting Started
---

As previous mentioned this project is not a wordpress plugin but rather a library. To use it you need to either clone it or download it from github (https://github.com/walisc/wpAPI)

Once downloaded place the wpAPI folder anywhere in your theme or plugin  you are working on. By convention I usually place it in a src directory, or the root of the folder

Once moved, you can `include` the wpAPI.php file in your main class. ([plugin_name].php for plugins and functions.php of themes). Once all refernces have been set  you can create a instance of wpAPI, which you can the use to create *page types*.

![1530108009101](/images/gts_code_image.png)

**Note:-** for the example above I used Obejct Oreiented Programing to create my plugin. This is becuase I'm going to work more on this example and wanted the code to be well oragnised. You could have however, created the custom post direct by coping the code in the Introduction section.  