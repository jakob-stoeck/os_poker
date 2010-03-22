# $Id: README.txt,v 1.2 2009/10/06 21:17:03 starnox Exp $

==============================
Info
==============================

Simple CDN re-writes the URL of certain website elements (which can be extended using plugins) for use with a CDN Mirror service. Setup literally takes about a minute. 

==============================
Installation
==============================

 1. Drop the simplecdn folder into the modules directory (/sites/all/modules/)
 4. Enable Simple CDN module (?q=/admin/build/modules) and optionally any element plugins you want to use your CDN Mirror with.
 5. Make sure you change the module settings so it works with your site (?q=admin/settings/simplecdn), you'll need to setup at least one mirror.

Required Modules:
------------------------------

 - None

==============================
Custom Code Use
==============================

Pop this code to rewrite a URL.

| simplecdn_rewrite_url($url, $element_id); ?>

 1. $url - the URL you want to convert to use with the CDN Mirror
 3. $element_id - this is registered by plugins or other modules, examples include: image, imagecache, mp3player etc.

==============================
For developers wanting to integrate Simple CDN with their modules 
==============================

Integration is very easy, you only need two bits of code. One an invoke function to the API so Simple CDN can register your element as something which can be used.

| /**
|  * Implementation of hook_simplecdnapi().
|  */
| function yourmodulename_simplecdnapi($op) {
|   switch ($op) {
|     case 'load':
|       return array(
|         'eid' => 'yourmodulename',
|         'name' => 'Your Module Name',
|       );
|       break;
|   }
| }

Next you will need to place this code where you need a URL to be processed by Simple CDN

| if (module_exists('simplecdn')) {
|   $url = simplecdn_rewrite_url($url, 'yourmodulename');
| }

==============================
The Future  
==============================

If you have any questions, issues, or feature suggestions then please do leave feedback on the project page (http://drupal.org/project/simplecdn)

==============================
Sponsorship 
==============================

This module is sponsored by Alpha International (http://www.alpha.org), Holy Trinity Brompton (http://www.htb.org.uk) and CoreDesigns (http://www.coredesigns.co.uk)