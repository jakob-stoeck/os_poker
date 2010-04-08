Dynamic CSS
----------
For each page, additional CSS files are loaded dynamically using the template
suggestions as filename. For instance, the page-front.css is loaded on the
front page.

Multi-languages support
-----------------------
For each theme's CSS file, a language version can be provided by appending a
dash and the language to the filename. For instance, the file front-page-en.css
will be included for the front page when language is English. This allow easy
image replacement of text. See for instance front-page-en.css

The language code is also used as class for the body element. So language
dependent CSS can also be placed in global CSS files.

TODO
----
 - Overrides home_signup to use image replacement and multi-languages support
   (in front-page-en.css)