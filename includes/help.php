<h3>Including the slider on your pages</h3>
<h4>Getting started</h4>
To test the set up, include the short code <strong>[the-slider]</strong> anywhere on a page. This should show you the demo slider.
<br />
<h4>The syntax</h4>
If that works, we can move to the next step. You can add parameters to the short code to control the behaviour of the slider, the syntax is as follows:
<br />
<strong>[the-slider parameter1=value1,parameter2=value,paramter3=value3,...]</strong>
<br />
<h4>The parameters</h4>
<ul>
<li>- cat: post category from which to select the posts</li>
<li>- max: maximum number of posts to include</li>
</ul>
<h4>Examples</h4>
<strong>[the-slider cat=3,max=4]</strong> will include 4 posts of post category 3 in the slider
<br />
<h3>Including the slider in your theme</h3>
To include the slider in your theme, include the following code:
<br />
<br />
<?php highlight_string('<?php the_slider(); ?>'); ?>
<br />
<br />
You can use the same parameters as described here above and pass them as an array to the function. So for example to display 4 posts from category 3 in the slider, you can use:
<br />
<br />
<?php highlight_string('<?php the_slider(array("cat" => 3,"max" => 4)); ?>'); ?>
<br />
<h3>FAQ</h3>
<h4>Can I include the slider multiple times on a page?</h4>
Yes, absolutely, you can put the slider short code anywhere on a page and multiple times, using different parameters for each slider.
<h4>How do I add images?</h4>
Simply set a featured image for your post. The slider will pick up the featured image and display it. Best sizes are 400px by 250 px.