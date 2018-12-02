/**
 * Copyright (c) 2017-present, Facebook, Inc.
 *
 * This source code is licensed under the MIT license found in the
 * LICENSE file in the root directory of this source tree.
 */

//the config exact same format as this file
var localConfig = require("./siteConfig.local")

const siteConfig = {
  title: 'WPooW' /* title for your website */,
  tagline: 'WordPress Object Oriented Wrapper',
  url: 'http://wpoow.devchid.com' ,
  baseUrl: '/',
  gaTrackingId: localConfig.gaTrackingId,

  projectName: 'wpOOW',
  organizationName: 'BatanaSoftware',
  // For top-level user or org sites, the organization is still the same.
  // e.g., for the https://JoelMarcey.github.io site, it would be set like...
  //   organizationName: 'JoelMarcey'

  // For no header links in the top nav bar -> headerLinks: [],
  headerLinks: [
    {doc: 'Introduction', label: 'Docs'},
    {href: 'https://github.com/walisc/wpAPI', label: 'GitHub' },
    {href: 'https://github.com/walisc/WPooWWorkshop', label: 'WordCamp CapeTown 2018 - Talk Material' },
    {href: 'mailto:chido@batanasoftware.com', label: 'Contact' }
  ],

  /* path to images for header/footer */
  favicon: 'img/favicon.png',

  /* colors for website */
  colors: {
    primaryColor: '#0087be',
    secondaryColor: '#00aadc',
  },

  /* custom fonts for website */
  /*fonts: {
    myFont: [
      "Times New Roman",
      "Serif"
    ],
    myOtherFont: [
      "-apple-system",
      "system-ui"
    ]
  },*/

  // This copyright info is used in /core/Footer.js and blog rss/atom feeds.
  copyright:
    'Copyright © ' +
    new Date().getFullYear() +
    ' BatanaSoftware',

  highlight: {
    // Highlight.js theme to use for syntax highlighting in code blocks
    theme: 'default',
  },

  // Add custom scripts here that would be placed in <script> tags
  scripts: ['https://buttons.github.io/buttons.js'],

  /* On page navigation for the current documentation page */
  onPageNav: 'separate',

  /* Open Graph and Twitter card images */
  ogImage: 'images/intro_code_meta_main.png',
  description:'This WordPress Object Oriented Wrapper aims to simplify the process of creating custom plugins and themes by providing a object-oriented api which abstracts common tasks associated with this. '

  // You may provide arbitrary config keys to be used as needed by your
  // template. For example, if you need your repo's URL...
  //   repoUrl: 'https://github.com/facebook/test-site',
};

module.exports = siteConfig;
