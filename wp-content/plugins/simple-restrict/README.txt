=== Simple Restrict ===
Contributors: awakensolutions
Tags: restrict, hide, permission, authorization, restrict pages, hide pages, restrict content, hide content, user permission, page permission, user permissions, page
Donate link: https://www.awakensolutions.com/donation
Requires at least: 3.4
Tested up to: 5.2.2
Stable tag: 1.2.5
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Restrict pages based on permissions assigned to pages and granted in user profiles.

== Description ==

This plugin allows you to easily mark certain pages with "Permissions" and only users with those permissions will be allowed to see the contents of the page.

* **Page Permissions:** This plugin adds a new Permissions taxonomy to your pages. Administrators can create/assign new permissions from the Edit Page screen (you can also use the Quick Edit link). You can add/edit/delete permissions from the Permissions sub-menu under the Pages menu. Pages with no assigned permissions can be seen by everyone.

* **User Permissions:** Administrators can add/remove permissions from a user using the checkboxes on the Edit User screen. The All Users page has a column that shows the permissions assigned to each user.

* **Restriction Message:** If a page has permissions assigned, the content will only be visible to users that have one of those same permissions assigned. Otherwise, the content will be replaced by a generic message or a custom message which can be defined in the plugin settings using the standard WordPress editor (including the ability to add media and formatting).

* **Redirect to login:** Instead of a restriction message, you can choose to have users get redirected to the login page.


== Installation ==

1. Upload the contents of `simple-restrict.zip` to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress


== Frequently Asked Questions ==

= How do I restrict access to a page? =

Go to the Edit Page screen for the page you want to restrict, and find the new "Permissions" section. Create and assign a new permission (e.g. "Gold subscriber") and update your page. Now this page content will only be viewable by users who have this same permission assigned to them.

= How do I allow a user to see a restricted page? =

If a page is restricted, it's content will only be viewable by users who have this same permission assigned to them. To assign a permission to a user, go to the Edit User page and you will see all possible permissions listed. Put a checkmark beside the Permissions you want to assign to this user. Save the changes, and the user will be allowed to see the page.

= If a page has multiple permissions assigned, does a user need to have all those permissions assigned to her/him in order to see the page? =

A user only needs to have one of the page permissions assigned to her/him in order to see that page. For example, if a page is assigned the permission "Gold subscriber" and another permission "Silver subscriber", a user who is assigned the permission "Silver subscriber" will see the page.

= What about pages with no permissions assigned to them? =

They are visible to everyone.

= Will this plugin restrict posts? =

No. This plugin is only for pages.

= Will this plugin restrict pages that use custom page templates? =

It depends. If your page template uses the_content() to retrieve the main content, and does not loop through any posts, then yes this plugin will restrict the page. Otherwise, see the answer below.

= Will this plugin restrict pages that show posts (archive pages)? =

No. This plugin hides the main content of the page (the_content()). Any page template that loops through posts, be they default posts or custom post types, will continue to show those posts. A more advanced content restriction plugin (usually paid) would be required for your needs.

= What WordPress user capabilities are required for the different functionalities of this plugin? =

The [manage_options](https://codex.wordpress.org/Roles_and_Capabilities#manage_options) capability is required to edit plugin settings.

The [edit_users](https://codex.wordpress.org/Roles_and_Capabilities#edit_users) capability is required to assign permission to users.

Any user who has the rights to edit a page also has the rights to assign permissions to the page and create new permissions.

= How can I contribute to the code? =

The plugin is [on GitHub](https://github.com/GitHubGreg/SimpleRestrict), feel free to submit a pull request.

= What languages does this plugin support, and how can I help translate it? =

This plugin was released in English and French, and anyone can add additional translations [from here](https://translate.wordpress.org/projects/wp-plugins/simple-restrict).


== Screenshots ==

1. New Permissions taxonomy added to pages
2. Permission management screen
3. Permissions metabox on the Edit Page screen
4. Permissions being added using page Quick Edit
5. Permissions column in the Users screen
6. Permissions assigned on the New User screen (and permissions can be edited for existing users)
7. Settings page where you can change the message that appears on restricted pages (including the ability to add media and formatting). Note: You can now also choose to redirect the user to the login page instead of showing the restriction message.


== Upgrade Notice ==

= 1.0.0 =
* First installation


== Changelog ==

= 1.0.0 =
* Initial release

= 1.0.1 =
* Updates to translations

= 1.0.2 =
* Switch to translate.wordpress.org.
* Testing on WordPress 4.5.

= 1.0.3 =
* Updates to readme and descriptions.

= 1.0.4 =
* Update to show Tested up to WordPress 4.9.4

= 1.1.0 =
* Adds option to redirect user to login page instead of showing the restriction message (thanks to ClearPathDigital)
* Updates to readme, description and FAQ to include GitHub URL.
* Tested up to WordPress 4.9.8

= 1.2.0 =
* Allows restriction of homepage

= 1.2.1 =
* Fixes a bug that caused the default restriction message to be '' (empty text) instead of the plugin's usual default.

= 1.2.2 =
* Adds compatibility with WordPress 5.0 (Gutenberg editor)

= 1.2.3 =
* Minor bug fix with Permissions column on Users page

= 1.2.4 =
* Changing the WordPress capability required for editing a user's permissions from manage_options to edit_users.

= 1.2.5 =
* Fixes a bug that would erase user permissions when they edited their own profile.