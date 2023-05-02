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
Configure message for new students, new teachers, low use teachers. Lose use is defined
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
