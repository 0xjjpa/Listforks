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
  		"get": {dataType: 'json', type: 'GET'},
      "post": {dataType: 'json', type: 'POST'}, 
      "put": {dataType: 'json', type: 'PUT'},
      "delete": {dataType: 'json', type: 'DELETE'}
  	};
  
    var baseUrl = '/app_dev.php/';
  	//var baseUrl = 'http://localhost:3501/mocks/';
  	self.response = ko.observable(null);

  	self.call = function(module, method, id, container, message) {
      
      /*
      console.log(module);
      console.log(method);
      console.log(id);
      console.log(container);
      console.log(message);
      */

      var restUrl, containerModule = module;
      restUrl = baseUrl + module;

      if(id) {
        restUrl += "/" + id;
        containerModule = module + "-view";
      } 
  		 
       var restOptions = registeredMethods[method];
       restOptions.url = restUrl;
       restOptions.data = message;

       restOptions.success = function(data) {
        if(method !== 'delete') {
          container(data);  
        }
        self.response({
          success: true,
          module: containerModule
        });
      }

      $.ajax(restOptions);
  	}

  	return self;
  }

  return l;
})(Listforks || {})