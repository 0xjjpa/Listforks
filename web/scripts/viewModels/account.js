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
      console.log(data);
      window.userAccountId = ko.observable(data.accountId);
    })

    self.init = function() {
      return self;
    }

    return self.init();
  }

  

  return l;

})(Listforks || {})