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

    var showModule = function(module, callback) {
      if(callback) {
        $("#"+module).fadeIn('slow', callback);
      } else {
        $("#"+module).fadeIn();  
      }
    }

    var hideLoading = function() {
      $("#loading-holder").fadeOut();
    }

    var showLoading = function() {
      $("#loading-holder").fadeIn();
    }

    var hideModules = function() {
      $(".module").fadeOut();
    }

    self.startRequest = function() {
      hideModules();
      showLoading();
    }

    self.endRequest = function(module) {
      showModule(module, window.disquesFunction);
      hideLoading();
      ;
    }
    
  	self.init = function() {
  		return self;
  	}

  	return self.init();
  }

  return l;

})(Listforks || {})