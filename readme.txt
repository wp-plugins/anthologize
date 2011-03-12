=== Plugin Name ===
Contributors: oneweekonetool, boonebgorges, digitaleffie, gossettphd, janaremy, jcmeloni, jeremyboggs, knoxdw, patrickmj, patrickrashleigh, sramsay, zmccune, chnm
Donate link: http://anthologize.org/
Tags: book, pdf, tei, epub, publish, ebook
Requires at least: 3.0
Tested up to: 3.0.1
Stable tag: 0.5-alpha

Use the power of WordPress to transform your content into a book.

== Description ==

Anthologize is a free, open-source, WordPress-based platform for publishing. Grab posts from your WordPress blog, pull in feeds from external sites, or create new content directly in Anthologize. Then outline, order, and edit your work, crafting it into a coherent volume for export in several ebook formats, including PDF, EPUB, and TEI.

Visit [anthologize.org](http://anthologize.org/ "Anthologize") to learn more about Anthologize.

== Installation ==

The best way to install Anthologize is via the Add New link under Dashboard > Plugins.

To install Anthologize manually, follow these steps.

1. Upload the `anthologize` directory to `/wp-content/plugins/`
1. Make sure the `/anthologize/templates/epub/temp/` directory is writable by the server
1. Activate Anthologize through the WordPres 'Plugins' menu
1. Visit Dashboard > Anthologize to start compiling your project

If you're upgrading manually from a previous version of Anthologize, please be sure to deactivate your existing plugin before replacing it with the new files, and reactivate after uploading.

== Screenshots ==

1. The Anthologize Project Organizer screen

== Usage ==

Many optimizations to the PDF export have been added, but resource limits on the server will always be a potential issue. Here are some tips for large exports:

1. Include page breaks between parts and items. This appears to reduce the memory that the PDF classes (TCPDF) require.
2. Add the following lines at the top of wp-setting.php in your WordPress installation, above define( 'WPINC', 'wp-includes' );

ini_set('max_execution_time', '180');
ini_set('memory_limit', '128M');

The latest release of PHP has a default memory limit of 128M, but this might not be in place on your server. Increasing the execution time (measured in seconds) can also help.
In a hosted server environment, these might be disabled, and/or you might make the admins mad at you for increasing the resources Anthologize consumes, thus hurting performance for everyone else on your server. It might be worth consulting your hosting company before increasing these resource limits and exporting projects on a regular basis.

Cover images in ePub output.

To add your own cover images, just upload them to the anthologize/templates/covers directory and they will appear as options in the export screen. Make sure they are readable by the server.



== Changelog ==

= 0.5.1-alpha = 

* many optimizations to PDF export
* added anthologize logo and part-item breadcrumbs to PDF output
* partially OOified epub export
* added part-item nesting to epub ToC
* added cover image option to epub output (might not work in all readers. standards-schmandards)


= 0.5-alpha =
* Code name "Gabardine"
* anthologize_register_format() API allows third-party developers to register their output-format plugins and options
* Newly added theming functions allow plugin developers to use familiar WordPress loops for creating new output formats
* Improved character encoding all-around
* Increased support for Korean, Japanese, Chinese text
* RTF export format discontinued in favor of a more stable HTML output (RTF facilities will likely reappear in a future version).
* New post filters on the project organizer screen: filter by date, filter by post type
* Minimize/maximize parts to make project editing easier
* Add multiple items to parts by dragging the Posts header on the project organizer screen
* Linked Table of Contents and better pagination in PDF
* Improved support for Gravatars in exports
* Methods added to the TEI class that allow for some automated indexing
* Many bugfixes and stability enhancements


= 0.4-alpha =
* Better PHP error handling for increased export reliability
* Better character encoding in output formats
* Better image handling in PHP export
* Required compression libraries for ePub are now bundled with Anthologize
* Project organizer screen improvements: Anthologize remembers your last used filter when you return to the page; a bug related to item ordering was fixed; "Are you sure?" message added to the Delete Project button; better handling of item names with colons and other characters
* Export screen improvements: project metadata (such as copyright information) is saved; selecting projects from the dropdown automatically pulls in saved metadata
* Namespaced WordPress post type names to ensure compatibility with other plugins
* Anthologize content is now set to 'draft' by default, keeping it out of WordPress searches and reducing conflict with plugins hooking to publish_post
* Frontmatter added to PDF export
* Improved TEI output

= 0.3-alpha =
* Initial public release

== Who built this? ==

Anthologize was built during [One Week | One Tool](http://oneweekonetool.org/ "One Week | One Tool"), an NEH Summer Institute at George Mason University's [Center for History and New Media](http://chnm.gmu.edu/ "CHNM")
