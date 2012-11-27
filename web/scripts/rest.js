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
  		get: $.getJSON,
      post: $.post
  	};
  
    var baseUrl = '/app_dev.php/';
  	//var baseUrl = 'http://localhost:3501/mocks/';
  	self.response = ko.observable(null);

  	self.call = function(module, method, id, container, message) {
      //var module = module.replace(/-/ig, "/");
      var restUrl, containerModule = module;
      restUrl = baseUrl + module;

      if(id) {
        restUrl += "/" + id;
        containerModule = module + "-view";
      } 
  		 
       console.log(module);
       console.log(method);
       console.log(id);
       console.log(container);
       console.log(message);
  		var restCall = registeredMethods[method];
      
  		restCall(restUrl, message, function(data) {
  			container(data);
  			self.response({
  				success: true,
  				module: containerModule
  			});
  		})
  	}

  	return self;
  }

  return l;
})(Listforks || {})