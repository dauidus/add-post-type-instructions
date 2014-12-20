Add Post Type Instructions
============================

== Tags ==
instructions, instruction, assist, direct, direction, directions, assistive, post type, help, metabox, insert text, wysiwyg, content, features, format, attributes, author, trackbacks, excerpt

== Tested up to: ==
4.2-alpha

== Description ==

Add Post Type Instructions allows admins to easily set instructional context for metaboxes and more on pages, posts or custom post types.  Currently it supports adding instructional text in the following areas on the add/edit screen:
** **
* below the title field
* author metabox
* featured image metabox
* excerpt metabox
* trackbacks metabox
* custom fields metabox
* page attributes metabox
* post format metabox
* comments metabox
* revisions metabox

APTI also allows admins to set default content within the WYSIWYG editor, per post type.

APTI uses OOP standards to add options only for those metaboxes which are supported for each post type and to execute code only on those pages where it is needed.  It works especially well for sites with many custom post types that require content to be entered in a specific way (ie. when a post type requires a specific page template or when the absence of a featured image will break the intended look of a post).  Think of any theme or plugin that supports an image slider powered by a required featured image, and you can surely see where APTI can come in handy.

To be clear, APTI does absolutely nothing to the front-end of your site.  It simply adds instructional context to the add/edit page/post admin screen so your clients and site editors might better understand how content is to be added.

APTI works with multisite networks and allows users to define settings on a per-site basis.

= Coming soon =
* add support for categories and tags
* Help tab on settings page about unsupport features and how to support them
* Translations

APTI will work with drag-n-drop builders such as Visual Composer, but the author cannot recommend its use with them.  This will be addressed in a future release.

= Suggestions are welcome =
* email the author at dave@dauid.us

= Follow Development on Github =
* https://github.com/dauidus/add-post-type-instructions

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
2. Search for "Post Type Instructions"
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

Simply delete this plugin to remove all data associated with it.  Deactivating the plugin will keep all plugin data saved in the database, but will not remove it.

== Screenshots ==

1. Settings page automatically recognizes post types and supported post type features.
2. Non-intrusive instructive text can be easily added to multiple locations for any post type.

== Changelog ==

= 2.0 =
* major changes to settings logic
* performance optimizations, expecially for multisite
* better multisite uninstallation
* support for comments and revisions
* change the_editor_content to default_content for WYSIWYG field

= 1.0.3.3 =
* update language file

= 1.0.3.2 =
* remove faulty code until fixed

= 1.0.3.1 =
* bugfixes

= 1.0.3 =
* restrict settings page to users with manage_options capability

= 1.0.2 =
* initial public release
* add support for trackbacks and excerpt
* add more advanced styles to appropriate admin pages
* better organize plugin code for readability
* change plugin_slug to better reflect plugin name

= 1.0.1 =
* add support for page attributes, custom fields, author, post formats
* optimize code for scalability
* add multisite support

= 1.0 =
* initial development release

== Upgrade Notice ==

= 2.0 =
This is a major update. Visit plugin settings page immediately after updating.

= 1.0.3 =
Update to add support for categories and tags metaboxes.

= 1.0.2 =
This version adds better styles, features and defines a more appropriate plugin_slug.

= 1.0.1 =
This version adds multisite support and deactivation/uninstall actions.

