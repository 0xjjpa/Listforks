'use strict';

/**
* Listforks Module
* @module Listforks
**/

var Listforks = (function(l) {

  /**
  * View Model for Listforks application
  * @class userViewModel
  * @constructor
  **/ 
  l.userViewModel = function() {

    var UserGUI = function(data, parent) {
      var self = {};

      self.parent = ko.observable();

      self.init = function(data, parent) {
        return self;
        self.parent(parent);
      }

      return self.init(data, parent);
    }

    var self = {};

    self.getContainer = ko.observableArray([]);  
    self.users = ko.observableArray([]);
    self.user = ko.observable();

    self.getContainer.subscribe(function(data){
      if($.isArray(data)) { // GET []

        var users = ko.utils.arrayMap(data, function(rawUser) {
          return new UserGUI(rawUser, self);
        });
        self.lists.push.apply(self.lists, lists);

      } else { // GET {}  
        var user = new UserGUI(rawUser, self);
        self.list(list)
        self.lists.push(list);
      }
    });

    self.init = function() {
      return self;
    }

    return self.init();
  }

  return l;

})(Listforks || {})