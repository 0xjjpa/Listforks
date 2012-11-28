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

    var privateExecutionQueue = ko.observableArray([]);

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
      $(".module").empty();
    }

    var runExecutionQueue = function() {
      var currentActions = privateExecutionQueue.removeAll();
      ko.utils.arrayForEach(currentActions, function(action) {
        var command = action.command; 
        var args = action.args;
        var context = action.context;
          //console.log(action);
        action.command.apply(action.context, action.args);
      });
    }

    self.call = function(command, args, context) {
      var action = {
        command: command,
        args: args,
        context: context
      }
      privateExecutionQueue.push(action)
    }

    self.startRequest = function() {
      hideModules();
      showLoading();
    }

    self.endRequest = function(module) {
      if(module === "lists-view") {
        showModule(module, window.disquesFunction);  
      } else {
        showModule(module);  
      }
      
      //console.log(DISQUS);
      hideLoading();
      //console.log("ALL LOADED!");
      runExecutionQueue();
    }
    
    self.init = function() {
      return self;
    }

    return self.init();
  }

  return l;

})(Listforks || {})