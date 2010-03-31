/* $Id: README.txt,v 1.1.4.8 2009/08/17 09:59:36 impetus Exp $ */
			     ShindigIntegrator: A module to integrate shindig into Drupal
     			  ===================================================================

 
Prerequisites 
============
This module requires Shindig. In order to use Shindig Module you must have the following:

- A Subversion client installed in order to checkout the code.
  Instructions for downloading and installing Subversion can be found here: 
  http://subversion.tigris.org/
- PHP 5.X.X
  * Latest PHP version can be downloaded from: http://www.php.net/downloads.php
  * Enable GD library for PHP installation. See http://in2.php.net/manual/en/image.setup.php for more
  details.
  * Enable Short tag in php.ini file of the PHP installation
- Apache Web server
  * Latest Apache web server can be downloaded from: http://httpd.apache.org/download.cgi
- MySQL 5.0
  * MySql can be downloaded from: http://www.mysql.com/downloads/index.html
- Drupal 5

Dependencies 
=============
- Apache Shindig code (http://svn.apache.org/repos/asf/incubator/shindig/branches/1.0.x-incubating/)
- user_relationships_api module (see user_relationships_api installation instructions) 
- Profile Module with following field names only. 
 ---------------------------------
   Title            Name(IN DB)
 ---------------------------------
  First name	  profile_fname	
  Last name	  profile_lname	
  Gender	  profile_gender	
  Date of Birth	  profile_dob	
  City		  profile_city	
  Country	  profile_country	
  Interested in	  profile_interest
 ----------------------------------
 Currently following fields are supported in the module. More fields can be easily added as per the 
 requirement in /shindig/php/src/social/sample/ShindigIntegratorDbFetcher.php.

 - The above field names are mandatory to get social data for this module to run successfully. If you 
   don't want to use profile fields as defined above, you need to modify the function getPeople() of 
   /shindig/php/src/social/sample/ShindigIntegratorDbFetcher.php, in order to run any open social 
   application.

Steps to use shindig_integrator module
=============================
- Download ShindigIntegrator module 
  http://ftp.drupal.org/files/projects/ShindigIntegrator-6.x-1.x-dev.tar.gz
- unzip/untar the code
- Copy the module /ShindigIntegrator/shindig_integrator to /drupal/sites/all/modules folder.
- Download the shindig code from svn repostiory 
  svn co http://svn.apache.org/repos/asf/incubator/shindig/branches/1.0.x-incubating/ shindig 
- Enable  Shindig Integrator in "Administer -> Site building -> Modules" page.

Note
=====
- This module  is currently compliant with shindig 1.0-incubating release.
- To make This module work with Signed gadgets, create your own OpenSSL private and public keys. 
- Put these key files at /shindig_integrator/shindig/php/certs/
- Put the private key phrase in /shindig_integrator/shindig/php/config/container.php

        'private_key_phrase' => 'your private key phrase',

- On some Unix variants,PHP's getcwd() will return FALSE if any one of the parent directories does not
  have the readable or search mode set, even if the current directory does. 
  Grant all required permissions to all parent directories 
  or 
  change "include_path" and "settings_php" manually in 
  shindig_integrator/shindig/php/config/local.php.
  *To make Shindig work properly*
     

