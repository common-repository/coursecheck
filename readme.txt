=== Coursecheck ===
Contributors: southcoastonline
Tags: coursecheck, coursecheck.com, course reviews, courses
Requires at least: 5.8
Tested up to: 6.6.2
Requires PHP: 7.4
Stable tag: 1.10.12
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl.html

Official reviews widget for training providers using coursecheck.com to collect student feedback.

== Description ==
The official Wordpress plugin for [coursecheck.com](https://www.coursecheck.com) training providers.

By installing this plugin on your website prospective customers can see your most recent reviews, how many reviews you have, together with your average rating.

= Company rating =

You can display your overall company rating anywhere on your site using a widget or shortcode.

To add a company rating to your sidebar navigate to **Appearance** > **Widgets** and add **Coursecheck Widget** to any widget area your theme supports.

To achieve the same result using a shortcode:

**[coursecheck company_id=X]**
where X is your coursecheck CompanyID

= Course rating =

You can display a course specific rating anywhere on your site using a widget or shortcode.

To add course rating to your sidebar navigate to **Appearance** > **Widgets** and add **Coursecheck Widget** to any widget area your theme supports. Then add the CourseID to a pages **Coursecheck** meta box (see screenshot below).

To achieve the same result using a shortcode:

**[coursecheck course_id=y]**
where y is your coursecheck CourseID

= Recent reviews carousel/slider =

You can display a carousel of your recent reviews on any page or post using the following shortcode:

**[coursecheck_reviews company_id=X]** 
where X is your Coursecheck CompanyID

To show recent reviews for a specific course add the course_id attribute

**[coursecheck_reviews company_id=X course_id=Y]** 
where X is your Coursecheck CompanyID and Y is one of your Coursecheck CourseIDs'

To change the speed of the carousel simply add a speed attribute

**[coursecheck_reviews company_id=X speed=10]** 
Slider speed set to 10 seconds

= Recent reviews list =

To add a list of recent reviews to your sidebar navigate to **Appearance** > **Widgets** and add **Coursecheck Recent Reviews** to any widget area your theme supports.

To add a list of your recent reviews to any page or post, use the following shortcode:

**[coursecheck_reviews company_id=X display=list]**  
where X is your Coursecheck CompanyID

To show recent reviews for a specific course add the course_id attribute

**[coursecheck_reviews company_id=X display=list course_id=Y]** 
where X is your Coursecheck CompanyID and Y is one of your Coursecheck CourseIDs'

To limit the number of reviews displayed add the optional num_reviews attribute:

**[coursecheck_reviews company_id=X display=list num_reviews=4]**  
where X is your Coursecheck CompanyID
(the maximum number of reviews that can be displayed is 10)

To display the list in a simple mobile friendly 2 column layout add the optional layout attribute:

**[coursecheck_reviews company_id=X display=list layout=columns]**  
where X is your Coursecheck CompanyID

= Styling & Colors =

We have included simple styling based on the Coursecheck branding that should work for most websites, but you can change pretty much any of this using [WordPress Customizer / Additional CSS](https://wordpress.com/support/editing-css/).

= Demos and Examples =

For more help and demos please see our [Coursecheck Plugin Demo](https://coursecheck.south-coast.online/) website.

== Installation ==
The simplest way to install the plugin is from your WordPress Dashboard. Navigate to **Plugins** > **Add New**, then use the search form in the top-right to search for **Coursecheck**. Click **Install Now** and once installed you need to **Activate** the plugin. 

== Frequently Asked Questions ==
= How do I install the plugin? =
The simplest way to install the plugin is from your WordPress Dashboard. Navigate to **Plugins** > **Add New**, then use the search form in the top-right to search for **Coursecheck**. Click **Install Now** and once installed you need to **Activate** the plugin. 

= How do I add a widget to show my company rating? =
When you add the coursecheck widget to your site, simply enter your coursecheck CompanyID into the widget.

= How do I add a widget to show ratings for one of my courses? =
First add the widget to the location you want using your coursecheck CompanyID. Then on your course page/post simply add the coursecheck CourseID to the page/post coursecheck properties box.

= How do show a carousel of recent reviews on a page? =
Edit your home page and put the following shortcode where you would like the carousel of recent reviews.
[coursecheck_reviews company_id=X]
where X is your Coursecheck CompanyID

= How can I change the speed of the carousel? =
The speed of the carousel defaults to 5 seconds before moving to the next slide, but you can override this setting by adding the speed attribute to the shortcode. Enter a value for the number of seconds.
[coursecheck_reviews company_id=X speed=10]
where X is your Coursecheck CompanyID with the speed/delay set to 10 seconds

= How do show a list of recent reviews on a page? =
Edit your home page and put the following shortcode where you would like the list of recent reviews.
[coursecheck_reviews company_id=X display=list]
where X is your Coursecheck CompanyID

= How do show a list of recent reviews in the side bar of my site? =
In Apppearance > Widgets add the Coursecheck Recent Reviews to any widget area your theme supports.
[coursecheck_reviews company_id=X display=list]
where X is your Coursecheck CompanyID

= How can I change the style and/or colors? =
We have included simple styling based on the Coursecheck branding that should work for most websites, but you can change pretty much any of this using [WordPress Customizer / Additional CSS](https://wordpress.com/support/editing-css/).

= Where can I see examples / demos? =
You can see examples on our [Coursecheck Plugin Demo](https://coursecheck.south-coast.online/) website.

== Screenshots ==
1. Adding company rating widget
2. Adding a course rating widget
3. Display company rating using a shortcode
4. Display a course rating using a shortcode
5. Display recent reviews in sidebar widget area

== Changelog ==
= 1.10.12 =
Release date: September 2024
* Tested to WP 6.6.2 

= 1.10.11 =
Release date: August 2023
* Added course specific link to recent reviews

= 1.10.10 =
Release date: August 2023
* Tested to WP 6.3 and PHP 8.2.6 

= 1.10.9 =
Release date: June 2023
* Tested to WP 6.2.2 and PHP 8.2.6 

= 1.10.8 =
Release date: June 2023
* Tested to WP 6.2 and PHP 8.2 

= 1.10.7 =
Release date: May 2022
* Fixed shortcode echo'ing to screen 

= 1.10.6 =
Release date: May 2022
* Added wp_kses to html output as per plugin code review 

= 1.10.5 =
Release date: May 2022
* Added sanitization and escaping as per plugin review 

= 1.10.4 =
Release date: May 2022
* Tested plugin with Wordpress 6.0 RC2 running php 8.0.18 

= 1.10.3 =
Release date: May 2022
* Tested plugin with Wordpress 5.9.3 running php 8.0.18  

= 1.10.2 =
Release date: May 2022
* Tested plugin with Wordpress 5.9.3 running php 8.0.18 

= 1.10.1 =
Release date: May 4th, 2021
* New: Added course specific calls to API allowing reviews carousel/lists to filter by course_id [coursecheck_reviews company_id=X course_id=y]
* New: Added simple responsive column layout option to recent reviews list [coursecheck_reviews company_id=X display=list layout=columns]
* New: Added course title to reviews list & carousel
* Tweak: Set reviews list link to more reviews target to open in new tab/window
* Tweak: Changed the way css/js is added to page to only load what is needed for shortcodes used

= 1.10.0 =
Release date: April 3rd, 2021
* New: Added speed attribute to the carousel shortcode
* Tweak: Changed more reviews link to open in new tab
* Tweak: Added css to stop user themes breaking our layout
* Tweak: Combined slick css files into a single file

= 1.9 =
* Update api feed character set encoding

= 1.8 =
* Change character set conversion of api feed to fix some broken characters

= 1.7 =
* Updated css to stop user theme css mucking up coursecheck styling 

= 1.6 =
* Update readme

= 1.5 =
* Update readme
* Added extra screenshot for reviews widget

= 1.4 =
* Updated recent reviews carousel and list
* Added extra screenshot for reviews widget

= 1.3 =
* Tested with WordPress 5.7
* Tested with PHP 5.6 (NOT recommended!)

= 1.2 =
* Added recent reviews carousel and list

= 1.1 =
* Added course specific shortcode

= 1.0 =
* Initial release