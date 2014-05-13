<?php
/**
 * Plugin Name: Google Maps Ready!
 * Plugin URI: http://readyshoppingcart.com/product/google-maps-plugin/
 * Description: Display custom Google Maps. Set markers and locations with text, images, categories and links. Customize google map without any programming skills
 * Version: 0.5.1.3
 * Author: Google Maps plugin
 * Author URI: http://readyshoppingcart.com
 **/
    require_once(dirname(__FILE__). DIRECTORY_SEPARATOR. 'config.php');
    require_once(dirname(__FILE__). DIRECTORY_SEPARATOR. 'functions.php');
    importClassGmp('dbGmp');
    importClassGmp('installerGmp');
    importClassGmp('baseObjectGmp');
    importClassGmp('moduleGmp');
    importClassGmp('modelGmp');
    importClassGmp('viewGmp');
    importClassGmp('controllerGmp');
    importClassGmp('helperGmp');
    importClassGmp('tabGmp');
    importClassGmp('dispatcherGmp');
    importClassGmp('fieldGmp');
    importClassGmp('tableGmp');
    importClassGmp('frameGmp');
    importClassGmp('langGmp');
    importClassGmp('reqGmp');
    importClassGmp('uriGmp');
    importClassGmp('htmlGmp');
    importClassGmp('responseGmp');
    importClassGmp('fieldAdapterGmp');
    importClassGmp('validatorGmp');
    importClassGmp('errorsGmp');
    importClassGmp('utilsGmp');
    importClassGmp('modInstallerGmp');
    importClassGmp('wpUpdater');
	importClassGmp('toeWordpressWidgetGmp');
	importClassGmp('installerDbUpdaterGmp');
	importClassGmp('templateModuleGmp');
	importClassGmp('templateViewGmp');
	importClassGmp('fileuploaderGmp');
	importClassGmp('recapcha',			GMP_HELPERS_DIR. 'recapcha.php');
	importClassGmp('mobileDetect',		GMP_HELPERS_DIR. 'mobileDetect.php');

    installerGmp::update();
    errorsGmp::init();
 
    dispatcherGmp::doAction('onBeforeRoute');
    frameGmp::_()->parseRoute();
    dispatcherGmp::doAction('onAfterRoute');

    dispatcherGmp::doAction('onBeforeInit');
    frameGmp::_()->init();
    dispatcherGmp::doAction('onAfterInit');

    dispatcherGmp::doAction('onBeforeExec');
    frameGmp::_()->exec();
    dispatcherGmp::doAction('onAfterExec');
   
