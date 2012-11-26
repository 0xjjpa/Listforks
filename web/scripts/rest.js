'use strict';

/**
* Listforks Module
* @module Listforks
**/

var Listforks = (function(l) {

  /**
  * REST Client that retrieves the resources from the application.
  * @class restfulEngine
  * @constructor
  * @depends jQuery
  **/ 
  l.restfulEngine = function() {
  	var self = {};
  	var registeredMethods = {
  		get: $.getJSON
  	};
  
    var baseUrl = '/app_dev.php/';
  	//var baseUrl = 'http://localhost:3501/mocks/';
  	self.response = ko.observable(null);

  	self.call = function(module, method, container) {
  		var restUrl = baseUrl + module;
  		var restCall = registeredMethods[method] || $.getJSON;

  		restCall(restUrl, {}, function(data) {
        console.log(data);
  			container(data);
  			self.response({
  				success: true,
  				module: module
  			});
  		})
  	}

  	return self;
  }

  return l;
})(Listforks || {})