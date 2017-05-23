Easy Enrollment Plugin
===========
The Easy Enrollment plugin generates a 6 digit code for every course and group in your Moodle site.  Once activated on your course you can visit the Easy Enrollment Settings page and see all of your enrollment codes.  This plugin will also generate QR codes which can be downloaded and printed to use with younger students or in print materials.  Upon activition of the Easy Enrollment plugin the Fordson Moodle Theme will display all the necessary forms on the homepage so that students can enter enrollment codes and instantly be taken to the corresponding course.  

The QR Code reader requires SSL/HTTPS in order to function.  If you do not have SSL then disable the QR code option from the Easy Enrollment settings page.

Dearborn Public Schools is a K-12 school district in Dearborn, Michigan.  We serve over 19,000 students in Wayne County and we are big fans of Moodle.  We believe our work with Moodle can be of value to the Moodle community and in the spirit of open source we are sharing some of our work.  There is no warrenty that this will work on every server with every theme or setup.  We use it.  It works well.  We are sharing it to help others.

NOTE: While this can be made to work with any theme the only theme we will officially support is Fordson.  Adding the enrollment form and other functionality needed for other themes would require hacking their code to make the form appear on the homepage.  There are many themes out there and they all do things a bit differently.  We only use one theme and that is Fordson.

## Instructions Github
* Download from Github and unzip
* Rename folder to -  easy
* FTP or place "easy" folder into moodleroot/enrol/
* If you cannot ftp then zip the newly named easy folder and use the normal Moodle plugin installation upload
* Once installed go to Site Administration > Plugins > Enrollments and activate "Easy Enrollments".  You can also adjust any of the settings.  If you do not have SSL please TURN OFF QR Code reader as it will not function.
* With the plugin installed and activated you must add it to any pre-existing courses as an enrollment method at the course level.
* In an individual course, on the course enrollment method page for easy enrollment you can click on Settings and it will display all enrollment codes.

## Version 1.1
* Fixed missing language strings

## Version 1.0
* Removed font-awesome includes since Fordson for Moodle 3.2 and Moodle 3.3 have the font-awesome support included. If you intend to use this outside of Fordson you must include Font-awesome with your theme.
* Required Fordson theme version 1.4.4 to ensure users can see how to fully implement the plugin which requires the front-page enrollment form and other integrations to be useful.  The plugin by itself is fully functional but requires editing theme files to work as smoothly as seen in videos with the Fordson theme.

## Initial Release
* This is the first release.