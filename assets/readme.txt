=== Plugin Name ===
Contributors: dauidus
Donate link: http://dauid.us/
Tags: instructions, assistive, post type, help, metabox, insert text, wysiwyg, features, format, attributes, author, trackbacks, excerpt
Requires at least: 3.0.1
Tested up to: 4.0.1
Stable tag: 1.0.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Allows admins to easily set instructional text per post type.

== Description ==

Allows admins to easily set instructional text per post type.  Currently supports inserting text below the title field, setting content within the WYSIWYG editor and adding instructional text inside the following metaboxes: author, featured image, excerpt, trackbacks, custom fields, page attributes, post format.  Only adds options for the metaboxes supported for each post type.

== Installation ==

Installation from zip:

1. From wp-admin interface, select Plugins -> Add New
2. Click Upload
3. Click "Choose File" and select add-post-type-instructions.zip
4. Click "Install Now"
5. Activate the plugin through the 'Plugins' menu in WordPress
6. Add instructive text from the `settings -> Post Type Instructions` admin page

Installation from WordPress Plugin Directory:

1. From wp-admin interface, go to Plugins -> Add New
2. Search for "Instruct"
3. Click Install Now under the plugin name
4. Click Ok to install the plugin
5. Click Activate Plugin once installed
6. Add instructive text from the `settings -> Post Type Instructions` admin page

== Frequently Asked Questions ==

= Who does this plugin benefit most? =

I wrote this plugin to provide simple assistance for my clients as they publish content.  With just a quick look at any metabox, site managers (authors, editors...) can see a clear description of what will happen when they add content to that metabox.  I have used this to convey the ideal image size for featured images, explain what tags are, or even add default content to any post type.  I have also used this to add consice instructions immediately below the title field on any post type.  Hopefully, it will benefit both site admins and site managers.

= Does it support Multisite? =

Yes.  This plugin can be either network activated or activated individually for each site on a network.

= How can I delete all data associated with this plugin? =

Simply delete this plugin to remove all data associated with it.  Deactivating the plugin will keep all data saved in the database, but not remove it.

== Screenshots ==

1. Settings page automatically recognizes post types and supported post type features.
2. Instructive text can be easily added to multiple locations for any post type.

== Changelog ==

= 1.0.2 =
* initial public release
* add support for trackbacks and excerpt
* add more advanced styles to appropriate admin pages
* better organize plugin code for readability

= 1.0.1 =
* add support for page attributes, custom fields, author, post formats
* optimize code for scalability
* add multisite support

= 1.0 =
* initial development release

