WPooW Testing

Testing WPoow is a bit tricky as it is testing dynamically created content based on the code you write (i.e WPooW is generally designed to create UI elements). 
There are more logical parts we can test as well like save and retriving of data, but this is a bit furtile if we can test the created interface to input/update the data.

Because of this we us selenium to Test WPooW UI generation functions, and normal php unit test to test the logic. Since we will be using Selenium we also need to setup
a WordPress site that utilise our tests. For this you can use any WordPress site and Install the `WPooWTestsPlugin` found in this directory.

From there navigate to WPooW folder and run `composer run-test [site_url]`

- Plugin create a route that returns the directory
- Plugin returns the repository path for WPooW
- Looks at current path and see's if it matches
-- if not run
--- reads and updates the composer file in the library to the correct path and the runs composer install

- then locally run through selenium access the site, and run tests