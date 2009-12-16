/* $Id: README.txt,v 1.14 2008/08/20 13:26:11 smk Exp $ */

-- SUMMARY --

Invitations are important to create network effects and exponential growth of a 
community of interest. This module adds an 'Invite a friend' feature that 
allows your users to send and track invitations to join your site.

For a full description visit the project page:
  http://drupal.org/project/invite
Bug reports, feature suggestions and latest developments:
  http://drupal.org/project/issues/invite


-- REQUIREMENTS --

* Token module http://drupal.org/project/token


-- INSTALLATION --

1. Copy the invite module to your modules directory and enable it on the Modules
   page (admin/build/modules).

2. Give some roles permission to send invites at the Access control page
   (admin/user/access). The following permissions can be controlled:

   send mass invitations - Allows users to send an invitation to multiple
     recipients (this was formerly a setting known as "limit per turn").

   track invitations - To give users access to the overview pages and
     associated actions (withdraw etc). Useful to hide overviews from anonymous
     users.

   withdraw accepted invitations - This will allow your users to delete
     accepted invitations. It will also delete all invitations from/to a user
     upon termination of its account. Disable it to prevent users from deleting
     their account to be re-invited. With the help of the Cancel User Accounts
     module it is possible to terminate user accounts by withdrawing an
     invitation.
 
   view invite statistics - Allows users to view invite statistics on their
     profile pages as well as view the Top inviters/User rank block.

   view own invite statistics - Same as above, but limits viewing statistics to
     the user's own profile.

3. Invite adds a new registration mode called 'New user registration by
   invitation only' to the User settings page (admin/user/settings), which
   allows you to maintain a semi-private site. You can enable it if you need it.

4. Configure the module at User management > Invite settings
   (admin/user/invite). For an explanation of the configuration settings see
   below.


-- CONFIGURATION --

--- General settings ---

* Target role
  Allows to specify the role invited users will be added to when they
  register, depending on the role of the inviting user. The default is
  'authenticated user'.

* Invitation expiry
  Specify how long sent invitations are valid (in days). After an invitation
  expires the registration link becomes invalid.

--- Role settings ---

* Target roles
  Allows to specify an additional role invited users will be added to when they
  register, depending on the role of the inviting user.

* Invitation limit
  Allows to limit the total number of invitations each role can send.

--- E-mail settings ---

* Subject
  The default subject of the invitation e-mail.

* Editable subject
  Whether the user should be able to customize the subject.

* Mail template
  The e-mail body.

* From e-mail address
  Choose whether to send the e-mail on behalf of the user or in the name of the
  site.

* Manually override From/Reply-To e-mail address (Advanced settings)
  Allows to override the sender and reply-to addresses used in all e-mails.
  Make sure the domain matches that of your SMTP server, or your e-mails will
  likely be marked as spam.


-- USAGE --

To invite a friend :

1. Click the 'Invite your friends and colleages' link.
3. Fill in the e-mail address(es) of the person(s) you would like to invite,
   and add a personal message.
4. Press submit.
5. This will send an invitation e-mail which you can now track from the
   'Your invitations' page.

Invitations show up in one of the states accepted, pending, expired, and the
special case deleted.

* Accepted: Shows that the person you have invited has accepted the invitation
  to join the site. Click on the e-mail address to watch the user's profile
  page.
* Pending: The invitation has been sent, but the invitee has still not accepted
  the invitation.
* Expired: The invitation has not been used to register within the expiration
  period.
* Deleted: The user account has been terminated.

At any time, you may withdraw either pending or expired invitations.
Accepted invitations can only be withdrawn if the configuration allows you to.


-- INVITE API --

The Invite module exposes hook_invite() that allows any module to react to the
invite lifecycle.

function hook_invite($op, $args) {
  case 'invite':
    An invitation has been successfully send.
    $args['inviter']: The user account object of the person who did the
                      inviting.
    $args['email']:   The e-mail address of the user who got invited.
    $args['code']:    The tracking code of the invitation.

  case 'escalate':
    Invitee has accepted an invitation and has been promoted to the appropriate
    user roles.
    $args['invitee']: The user account object of the person who was invited.
    $args['inviter']: The user account object of the person who did the
                      inviting.
    $args['roles']:   An array of roles the invited person has been escalated
                      to.
   
  case 'cancel':
    Inviter has cancelled an invitation.
    $args['inviter']: The user account object of the person who did the
                      inviting.
    $args['email']:   The e-mail address of the user whose invitation got
                      cancelled.
    $args['code']:    The tracking code of the invitation.
}

There are several third-party modules that can react on invite events:

* Buddylist http://drupal.org/project/buddylist
  User Relationships http://drupal.org/project/user_relationships
  Inviter and invitee are automagically put on their respective buddy list.

* Userpoints http://drupal.org/project/userpoints
  Credit some points for sending registrations and/or when an invited user
  registers.


-- TROUBLESHOOTING --

When the site is set to allow new accounts by invitation only, it would be nice 
to remove the 'Create new account' tab that shows up if a user clicks on the 
'Request a new password' link. There seems no way to remove existing menu 
entries.

To solve this issue, you could add the following function to your template.php:

function phptemplate_menu_item_link($item, $link_item) {
  if ($link_item['path'] == 'user/register') return;
  return l($item['title'], $link_item['path'], !empty($item['description']) ? array('title' => $item['description']) : array(), isset($item['query']) ? $item['query'] : NULL);
}

This prevents the 'Create new account' menu item from being rendered.


-- CREDITS --

Original author:
  David Hill (tatonca)

Current maintainer:
  Stefan M. Kudwien (smk-ka)

Sponsored by UNLEASHED MIND
  Specialized in consulting and planning of Drupal powered sites, UNLEASHED
  MIND offers installation, development, theming, customization, and hosting
  to get you started. Visit http://www.unleashedmind.com for more information.

