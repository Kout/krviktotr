// ================================================
// PHP image manager - iManager 
// ================================================
// iManager - readme.txt
// ================================================
// Developed: net4visions.com
// Copyright: net4visions.com
// License: LGPL - see readme.txt
// (c)2005 All rights reserved.
// ================================================
// Revision: 1.2                   Date: 08/09/2005
// ================================================

---------------------------------------------------
 - Thank you
---------------------------------------------------

Let me take this opportunity to thank everbody who has contributed
to iManager - I could not have realized this project without the 
patient help of James, Alan, Johan(Spoke), and Slava.



---------------------------------------------------
 - Introduction
---------------------------------------------------

iManager allows you to manage your image files on your webserver.

With iManager you can manage your files/images on your webserver, and it
provides user interface to most of the phpThumb() functions.

The main functions are:

- multiple Wysiwyg editor integration
- supports standalone mode by starting iManager like imanager.php?mode=2
- select images on your webserver 
- create directories 
- rename, delete files 
- multiple file uploads with automatic thumbnailing
- resize images 
- crop - interactive move and resize of crop area 
- flip 
- rotate 
- add watermark text or image
- add image mask
- add overlay
- enhance image quality
- bevel, blur, border functions
- select filetype and quality (jpeg) of processed images

... and much more. 


If you need less features, have a look at the
net4visions' iBrowser.

 

---------------------------------------------------
 - Installation
---------------------------------------------------

iManager has been confirmed to work with the latest version of
Microsoft Internet Explorer and Firefox.

1. Prerequisites
---------------------------------------------------
   You will need to compile PHP with the GD library of image functions for iBrowser to work.
   If you use CSS styles for images and/or image caption, please make sure that the used css styles also exist in 
   your site's stylesheet AND the wysiwyg editor content area stylesheet.


   2. Permission settings
   ---------------------------------------------------
   Make sure the following directories have writing
   permission (chmod to 0755):

	iManager/scripts/phpThumb/cache - should there be any files already, plese delete those!!!
	iManager/temp

	all the image libraries you set up in the iManager config file!


   3. Configuration
   ---------------------------------------------------
   Check configuration settings
   The configuration of iManager if fairly easy - it depends a little
   on what wysiwyg editor you're using

   Setting up image libraries:
   ---------------------------

   You can set up your image libraries in two ways (static or dynamically):

   - static: set your libraries like:
  	$cfg['ilibs'] = array (	
		array (
			'value'   	=> '/dev/pictures/', 				
			'text'    	=> 'Site Pictures',
		),
		array (
			'value'   	=> '/dev/images/', 				
			'text'    	=> 'Gallery',
		),
	);
	


   - dynamically: set your libraries like:
	uncomment the following line in your config file - the following settings will
	automatically override the static libary settings

	$cfg['ilibs_dir'] 	= array('/dev/pictures/','/dev/images/');
	

	The aforementioned main directories will be scanned for sub-directories and
	all directories found will be listed as directories.


   4. WYSIWYG Interfaces
   ---------------------------------------------------

   You'll find some predefined interface files in the iManager/interfaces directory.
   As per now, interfaces for tinyMCE, SPAW, and FCKeditor are provided - I hope there will be more 
   in the near future.


   tinyMCE interface
   -----------------

   copy the provided interface file: tinyMCE.editor_plugin.js file into your iManager plugin
   directory and rename it to "editor_plugin.js". Make a copy of it and rename it to editor_plugin_src.js.

   adding plugin to tinyMCE:

	tinyMCE.init({ 
		... 
		plugins : "imanager", 
		theme_advanced_buttons3_add : "imanager", 
	... 
	}); 


   For further information on how to use a plugin with tinyMCE be it iManager or any other plugin,
   please see the tinyMCE instructions manual!


   FCKeditor interface
   -------------------

   copy the provided interface file: FCKeditor.editor_plugin.js file into your FCKeditor iManager plugin
   directory and rename it to "fckplugin.js".

   In the fckconfig.js file, add 'iManager' to the FCKConfig.ToolbarSets. Register the iManager plugin with
   the following statement: FCKConfig.Plugins.Add( 'iManager') ; 

   For further information on how to use a plugin with FCKeditor, be it iManager or any other plugin,
   please see the FCKeditor instructions manual!

   
   Xinha interface
   -------------------

   copy the provided interface file: xinha.editor_plugin.js file into your Xinha iManager plugin
   directory and rename it to "imanager.js".

   add iManager to the following array: xinha_plugins = xinha_plugins ? xinha_plugins :
      [
       'CharacterMap',
       'ContextMenu',       
       'ListType',       
       'Stylist',      
       'TableOperations',
       'imanager'
      ];

   For further information on how to use a plugin with Xinha, be it iManager or any other plugin,
   please see the Xinha instructions manual!


   HTMLarea interface
   -------------------

   copy the provided interface file: HTMLarea.editor_plugin.js file into your HTMLarea iManager plugin
   directory and rename it to "imanager.js".

   load the iManager plugin as follows:
   	HTMLArea.loadPlugin("imanager");

   register the iManager plugin as follows:
	editor.registerPlugin(imanager);


   For further information on how to use a plugin with HTMLarea, be it iBrowser or any other plugin,
   please see the HTMLarea instructions manual!


   SPAW interface
   --------------

   unfortunately, the plugin integration into SPAW isn't as easy as with tinyMCE. However, if you follow the next
   steps, it shouldn't be a problem to get iManager to work with SPAW either.

	1. in the spaw directory, create a directory called "plugins" with a sub-directory called "iManager".
	   unzip all the iManager files into the "iManager" directory
	
	2. edit the following two files in the spaw/class directory and add it just before the
           SPAW_showColorPicker(editor,curcolor) line:

		IE: scripts.js.php
			<?php include $spaw_root . 'plugins/ibrowser/interface/SPAW.editor_plugin.js'; ?>

		Firefox: scripts+gecko.js.php		
			<?php include $spaw_root . 'plugins/imanager/interface/SPAW.editor_plugin.js'; ?>


	3. edit the following two file in the spaw/lib/toolbars/default directory
	   (if you don't use the default toolbar, use the one you use)
		- default_toolbar_data_inc.php
		- default_toolbar_data.gecko.inc.php

		array(
              		'name' => 'imanager',
              		'type' => SPAW_TBI_BUTTON
            	),

		if you like to not longer use the regular SPAW image function, just comment those lines.

	4. copy the four button images in the iManager/interface/images/spaw directory into the spaw/libs/themes/img directory

	5. in the spaw/lib/lang/en directory, edit the "en_lang_data.inc.php" file and add the following:

		'iManager' => array(
  		   'title' => 'iManager'
  		),

		This will create the title for the toolbar image button 	

  

		
