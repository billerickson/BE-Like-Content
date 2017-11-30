# BE Like Content #
![Release](https://img.shields.io/github/release/billerickson/be-like-content.svg) ![License](https://img.shields.io/badge/license-GPL--2.0%2B-red.svg?style=flat-square&maxAge=2592000)

**Contributors:** billerickson  
**Requires at least:** 4.1  
**Tested up to:** 4.9  
**Stable tag:** 1.1.0  
**License:** GPLv2 or later  
**License URI:** http://www.gnu.org/licenses/gpl-2.0.html

BE Like Content is a lean plugin for allowing visitors to mark favorite content. It's developer-friendly and very extensible. To see it in use, visit [my blog](https://www.billerickson.net/blog/).

## Installation ##

[Download the plugin here.](https://github.com/billerickson/BE-Like-Content/archive/master.zip)

## Customization ##

In your theme, use `be_like_content()->display()` to display the like button. It is unstyled, so you will need to style it yourself.

You can also customize it with the following filters:

* `be_like_content_settings` Customize the default settings. These currently include the supported post types and the text for zero, one, and many likes. `{count}` is a placeholder that's replaced with the actual count.
* `be_like_content_load_assets` Whether the JS file loads. If you place this in your theme - `add_filter( 'be_like_content_load_assets', '__return_false' )` - you can copy the JS file into your own file and tweak it as needed. Make sure you include both [be-like-content.js](https://github.com/billerickson/BE-Like-Content/blob/master/assets/js/src/be-like-content.js) and its dependency, [js.cookie.js](https://github.com/billerickson/BE-Like-Content/blob/master/assets/js/src/js.cookie.js).
* `be_like_content_display_count` Modify the display of the text inside the like link. On my site, I used this to add an SVG icon of a thumbs up.
* `be_like_content_popular_widget_args` Customize the query arguments for the "Popular Content" dashboard widget. It's currently set to show the 20 most popular post across all supported post types.
