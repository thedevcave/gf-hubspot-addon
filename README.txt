=== Gravity Forms - Hubspot Form Submission Add-On ===
Contributors: darkhousedevelopment
Tags: Gravity Forms, Hubspot, Hubspot API, Form Submission, Add-On
Requires at least: 5.6
Tested up to: 6.1
Requires PHP: 7.0
Stable tag: 1.1
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Connect Gravity Forms to Hubspot and automatically sync your form entries to a duplicate form in Hubspot.

== Description ==
**IMPORTANT: THIS PLUGIN REQUIRES GRAVITY FORMS TO ALREADY BE INSTALLED**

This plugin allows you to connect your Gravity Forms forms to duplicate forms in Hubspot CRM. Unlike the official Gravity Forms Hubspot Add-On, this plugin does not just submit the form entry values via the Contacts API with no record of the form submission. This plugin allows you to take the entry in Gravity Forms and submit it via the Forms API so you also have a form submission in Hubspot. This makes it a lot easier to trigger Workflows or other integrations with Hubspot because there is a specific action taking place, instead of properties just being updated.

After installing the plugin you simply connect and authorize the plugin to access your Hubspot account via the settings page and then you will be able to assign any Hubspot form to your Gravity Forms via the form settings page and then map all of the fields.

== Installation ==
1. Install and activate the plugin using the usual methods of searching through the WordPress plugins repo or by manually uploading and activating the plugin.
2. After the plugin is activated go to the Hubspot Form Submission settings page under the main Gravity Forms menu.
3. Click the *Authorize GF to Hubspot Add-on* button and follow the prompts in the popup window to select your Hubspot account and authorize the accompanying Hubspot App to access your account.
4. Once completed you can close the popup window. The WordPress admin page should refresh in the next 5-10 seconds, but if it doesn\'t you can refresh the page manually. You should now see a Thank You message and an Error Log in case of debugging. The add-on is fully connected and ready to use now!
5. Select any form in Gravity Forms and under Settings for the form there will be a new section called Hubspot Form Submission.
6. Select the matching form in Hubspot from the first dropdown field and click the Save Settings button.
7. You may need to refresh the page at this point for the Hubspot form fields to show up in the left column dropdown fields. 
8. Once the fields are available sync each of your Hubspot fields to their matching Gravity Form fields and click Save Settings once more.
9. Your form is now fully connected and if you submit a test submission through the Gravity Form you should see a duplicate form submission in Hubspot as well. If you don\'t see the submission or receive any errors return to the main Hubspot Form Submission settings page and view the Error Log.

== Screenshots ==
1. Main Hubspot Form Submission Settings Page
2. Form Specific Hubspot Form Submission settings section showing field mapping

== Changelog ==
= 1.1 =
* Added data conversions for consent checkboxes, regular checkboxes, and file upload field types.
* Minor styles updates.

= 1.0 =
* Initial version ready for launch!