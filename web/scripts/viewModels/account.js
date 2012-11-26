'use strict';

/**
* Listforks Module
* @module Listforks
**/

var Listforks = (function(l) {

  /**
  * View Model for Listforks application
  * @class viewModel
  * @constructor
  **/ 
  l.accountViewModel = function() {
    var self = {};

    self.getContainer = ko.observable({});
    
    self.getContainer.subscribe(function(data){
      //Do something when we get the account data
    })

    self.init = function() {
      return self;
    }

    return self.init();
  }

  

  return l;

})(Listforks || {})