# Onboarding notifications #

The onboarding notifications plugin allows admins to configure messages to be sent to
new teachers, new students, and low-use teachers to assist with getting started in the
application, as well as learning how to take advantage of features beyond the basic ones.

## Installing via uploaded ZIP file ##

1. Log in to your Moodle site as an admin and go to _Site administration >
   Plugins > Install plugins_.
2. Upload the ZIP file with the plugin code. You should only be prompted to add
   extra details if your plugin type is not automatically detected.
3. Check the plugin validation report and finish the installation.

## Installing manually ##

The plugin can be also installed by putting the contents of this directory to

    {your/moodle/dirroot}/local/onboarding

Afterwards, log in to your Moodle site as an admin and go to _Site administration >
Notifications_ to complete the installation.

Alternatively, you can run

    $ php admin/cli/upgrade.php

to complete the installation from the command line.

## Usage ##

Configure message for new students, new teachers, low use teachers. Low use is defined
as having nothing or only File resources in a course (and possibly the default News forum).

### New user notifications ###

The plugin listens for user_created events, and adds those users to the local_onboarding
table. A scheduled task runs once a day to find student and teacher roles for those users
and adds them to the records accordingly. Another scheduled task runs once a day to send 
configured messages to those users who have a role designated.

### Low use notifications ###

A scheduled task runs three times per year (Jan 15, May 15, Aug 15 to align with spring,
summer, and fall semesters) to find teachers in low use courses and sends them
the configured message. Teachers who are also enrolled in non-low use courses are excluded
from the notifications (i.e. teachers of metacourses will likely have empty courses,
but may have courses using more features and, therefore, not need additional onboarding).
Only one message is sent per user (versus one message per low use course).

### Cleanup ###
A scheduled task runs once a month to delete user records in the local_onboarding table
that have not been assigned a role and are one year or older.

### Tracking onboarding email link clicks ###

The plugin has the ability to track links included as part of onboarding emails. This is done using a redirect link and two database tables, local_onboarding_redirect_links and local_onboarding_link_clicks. The table local_onboarding_redirect_links contains the full URL for the site you would like the user to end up at. The table local_onboarding_link_clicks keeps track of which userids have clicked on which links. The redirect link must be in the format https://yourmoodlesite.edu/local/onboarding/redirect.php?id=xx&userid=%userid% where yourmoodlesite.edu is your site's wwwroot, xx is the id in the local_onboarding_redirect_links table that contains the full URL of the site you wish to redirect the user to, and %userid% is a wildcard that will be used to send the user's id to the local_onboarding_link_clicks table and should be kept in the URL as %userid%. The tracking URL must be in this format in order for link tracking to work. If you do not wish to track links, regular links can be used in onboarding messages.

## License ##

2023 Michelle Melton <meltonml@appstate.edu>

This program is free software: you can redistribute it and/or modify it under
the terms of the GNU General Public License as published by the Free Software
Foundation, either version 3 of the License, or (at your option) any later
version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY
WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A
PARTICULAR PURPOSE.  See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with
this program.  If not, see <https://www.gnu.org/licenses/>.
