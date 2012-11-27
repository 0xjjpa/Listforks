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

    self.getContainer = ko.observableArray([]);
    
    self.getContainer.subscribe(function(data){
      if(data._hasData) {
        var accountId = data.account.accountId;
      }
      window.userAccountId = ko.observable(accountId);
    })

    self.init = function() {
      return self;
    }

    return self.init();
  }

  

  return l;

})(Listforks || {})