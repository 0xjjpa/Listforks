'use strict';

/**
* Listforks Module
* @module Listforks
**/

var Listforks = (function(l) {

  /**
  * Describes the routing mechanism for the application
  * @class application
  * @constructor
  * @depends jQuery, SammyJs, ViewModels, restfulEngine 
  **/ 
  l.application = function() {
    var self = {};

    var viewsUrl = '/views/';
    var viewsExtension = '.html';

    var modules = {
      lists: "listViewModel",
      accounts: "accountViewModel"
    };

    var loadModule = function(module, method, applyBindings) {
      var viewModelName = modules[module];
      var activeViewModel = new l[viewModelName](); 

      var container = activeViewModel.getContainer; 
      self.client.call(module, method, container); 
      if(applyBindings) ko.applyBindings(activeViewModel, document.getElementById(module));
    }

    self.onResponse = function(response) {
      if(response.success) {
        self.application.showModule(response.module);
        self.application.hideLoading();
      }
    }

    self.registerCallbacks = function() {
      self.client.response.subscribe(self.onResponse);
    }

    

    self.loadAccountData = function()  {
      var module = "accounts";
      loadModule(module, "get");
    }

    self.application = null;
    self.client = null;

    self.routing = $.sammy(function() {

      this.get('#:module', function () {
        var module = this.params.module; 
        var $module = $('#'+module);
        
        $module.load(viewsUrl+module+viewsExtension, function() {
          loadModule(module, "get", true);
        });
          
      })

      // Base URL
      this.get('', function () { 
        this.app.runRoute('get', '#lists') 
      });
    })

    /**
    * Constructor for Routing Engine
    * @method init 
    * @return {Object}
    **/
    self.init = function() {
      self.application = new l.GUIEngine();
      self.client = new l.restfulEngine();
      self.registerCallbacks();

      self.loadAccountData();
      self.routing.run();
      return self;
    }

    return self.init();
  }

  return l;
})(Listforks || {})
