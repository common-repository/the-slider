=== The Slider ===
Contributors: zingiri
Donate link: http://www.zingiri.com/donations
Tags: slider, gallery, featured content, content, easing, featured, images, jquery, slider, slideshow, carousel
Requires at least: 2.1.7
Tested up to: 3.6.1
Stable tag: 1.1.0

The Slider is a catchy featured content slider ideal for showcasing your products and services.

== Description ==

Showing off the best content of your website or blog in a nice intuitive way will surely catch more eyeballs. Using an auto-playing content slider is the one of techniques to show your featured content. It saves you space and makes for a better user experience, and if you add a pinch of eye candy to it, then there's no looking back.

== Installation ==

1. Upload the `the-slider` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Include the code [the-slider] in any page to display the slider.
4. If you see the demo slider appear, you're good to go, check out the plugin control panel or head to [Zingiri](http://www.zingiri.com/plugins-and-addons/the-slider "Zingiri") for further instructions. 

== Frequently Asked Questions ==

= How do I include the slider on my pages? =

To test the set up, include the short code [the-slider] anywhere on a page. This should show you the demo slider.

If that works, we can move to the next step. You can add parameters to the short code to control the behaviour of the slider, the syntax is as follows:

[the-slider parameter1=value1,parameter2=value,paramter3=value3,...]
The parameters

* cat: post category from which to select the posts
* max: maximum number of posts to include

Examples:

* [the-slider cat=3,max=4] will include 4 posts of post category 3 in the slider

= Can I include the slider in my theme? =

To include the slider in your theme, include the following code:

<?php the_slider(); ?>

You can use the same parameters as described here above and pass them as an array to the function. So for example to display 4 posts from category 3 in the slider, you can use:

<?php the_slider(array("cat" => 3,"max" => 4)); ?>

= Can I include the slider multiple times on a page? =

Yes, absolutely, you can put the slider short code anywhere on a page and multiple times, using different parameters for each slider.

= How do I add images? =

Simply set a featured image for your post. The slider will pick up the featured image and display it. Best sizes are 400px by 250 px.

== Screenshots ==

1. The Slider in action on sample page in Twenty Eleven theme

== Changelog ==

= 1.1.0 =
* Verified compatibility with WP 3.6.1
* Included FAQ

= 1.0.6 =
* Verified compatibility with WP 3.5.2

= 1.0.5 =
* Fixed issue with menu not showing under Settings

= 1.0.4 =
* Verified compatibility with WP 3.5.1

= 1.0.3 =
* Fixed issue with including removed file

= 1.0.2 =
* Removed obsolete file http.class.php
* Verified compatibility with WP 3.4.2

= 1.0.1 =
* Replaced remote logo with local version

= 1.0.0 =
* Updated documentation

= 0.9.1 =
* First release

