'use strict';

/**
* Listforks Module
* @module Listforks
**/

var Listforks = (function(l) {

  /**
  * View Model for General application
  * @class appModel
  * @constructor
  **/ 
  l.GUIEngine = function() {
  	var self = {};

    self.showModule = function(module) {
      $("#"+module).show();
    }

    self.hideLoading = function() {
      $("#loading-holder").fadeOut();
    }
    
  	self.init = function() {
  		return self;
  	}

  	return self.init();
  }

  return l;

})(Listforks || {})