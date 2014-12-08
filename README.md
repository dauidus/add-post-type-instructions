Add Post Type Instructions
============================

Allows admins to easily set instructional text for pages, posts or custom post types.  Currently it supports adding instructional text below the title field, setting default content within the WYSIWYG editor and adding instructional text inside the following metaboxes: 
	author
	featured image
	excerpt
	trackbacks
	custom fields
	page attributes
	post format

This plugin uses OOP standards to add options only for those metaboxes which are supported for each post type and to execute code only on those pages where it is needed.  This helps to not only keep load response time quick, but also to alleviate user frustration by hiding unnecessary options.  It works especially well for sites with many custom post types that require content to be entered in a specific way (ie. when a post type requires a specific page template or when the absence of a featured image will break the intended look of a post).

To be clear, this plugin does absolutely nothing to the front-end of your site.  It simply adds instructional context to the add/edit page/post admin screen.

Add Post Type Instructions works with multisite networks and allows users to define settings on a per-site basis.

TAGS:
instructions, assistive, post type, help, metabox, insert text, wysiwyg, content, features, format, attributes, author, trackbacks, excerpt

COMPATIBLE UP TO:
4.0.1

WORKS WITH MULTISITE:
yes

FAQ:
Who does this plugin benefit most?
I wrote this plugin to provide simple assistance for my clients as they publish content.  With just a quick look at any metabox, site managers (authors, editors...) can see a clear description of what will happen when they add content to that metabox.  I have used this to convey the ideal image size for featured images, explain what tags are, or even add default content to any post type.  I have also used this to add consice instructions immediately below the title field on any post type.  Hopefully, it will benefit both site admins and site managers.

Does it support Multisite?
Yes.  This plugin can be either network activated or activated individually for each site on a network.

How can I delete all data associated with this plugin?
Simply delete this plugin to remove all data associated with it.  Deactivating the plugin will keep all plugin data saved in the database, but will not remove it.
    
CHANGELOG:
1.0.2
* initial public release
* add support for trackbacks and excerpt
* add more advanced styles to appropriate admin pages
* better organize plugin code for readability
* change plugin_slug to better reflect plugin name

1.0.1
* add support for page attributes, custom fields, author, post formats
* optimize code for scalability
* add multisite support

1.0
* initial development release

UPGRADE NOTICE:
1.0.2
This version adds better styles, features and defines a more appropriate plugin_slug.

1.0.1
This version adds multisite support and deactivation/uninstall actions.

TODO:
- check for administrator user role
    disable settings page for lesser roles
  
- add help tab on settings page
    explains why only certain fields are being shown for each post type (unsupported features)

- provide appropriate language definitions for the following:
    english

- add support for categories and tags
