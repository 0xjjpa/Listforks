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

    var User = function(data) {
      console.log(data);
      var self = {};
      self = data;
      return self;
    }

    var UserGUI = function(data, parent) {
      var self = {};

      self.parent = ko.observable();
      self.user = ko.observable();

      self.init = function(data, parent) {
        self.parent(parent);
        self.user( new User(data) );
        return self;
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
        self.users.push.apply(self.users, users);

      } else { // GET {}  
        var user = new UserGUI(rawUser, self);
        self.user(user)
        self.users.push(user);
      }
    });

    self.init = function() {
      return self;
    }

    return self.init();
  }

  return l;

})(Listforks || {})