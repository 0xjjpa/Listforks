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
	l.listViewModel = function() {

		var ListItem = function(data) {
			if(data) data.status = "updated";
			data = data || {};
			var self = {};
			self.id = ko.protectedObservable(data.id || -1);
			self.description = ko.protectedObservable(data.description || "Item Description");
			self.order = ko.protectedObservable(data.order || -1);
			self.status = ko.protectedObservable(data.status || "new");
			return self;
		}

		var List = function(data) {
			data = data || {};
			var self = {};
			self.id = ko.protectedObservable(data.id || -1);
			self.name = ko.protectedObservable(data.name || "List Name");
			self.description = ko.protectedObservable(data.description || "List Description");
			self.private = ko.protectedObservable(data.private || false);
			self.location = ko.observable(data.location || {});
			self.rating = ko.protectedObservable(data.rating || 0);

			self.items = ko.observableArray([]);
			self.filteredItems = ko.computed(function() {
				return ko.utils.arrayFilter(self.items(), function(item) {
					return item.status() !== "deleted";
				})
			});

			var items = data.items || [];
			var newItems = ko.utils.arrayMap(items, function(rawItems) {
				return new ListItem(rawItems);
			});

			self.items.push.apply(self.items, newItems);
			return self;
		}

		var ListGUI = function(data, parent) {
			var self = {};
			self.parent = ko.observable(parent);
			self.list = ko.observable();
			self.id = ko.observable(0);

			var stateMachine = ["editList", "displayList", "editListItem"];

			var perform = function(action, container) {
				var property, value;
				var selected = container();
				
				for (var property in selected) {
					if (selected.hasOwnProperty(property)) {
						value = selected[property];
						if (ko.isObservable(value) && value[action]) {
							value[action]();   
						}
					}
				} 
			}

			var goTo = function(template) {
				self.template(template);
			}

			var subGoTo = function(template) {
				self.subtemplate(template);
			}

			self.template = ko.observable("displayList");   
			self.subtemplate = ko.observable("displayListItem");   
			self.selectedListItem = ko.observable({});


			self.selectedSubtemplate = function() {
				return self.subtemplate();
			}

			self.selectedTemplate = function() {
				return self.template();
			}

			self.addListItem = function(list) {
				list.items.push(new ListItem());
				console.log(ko.toJS(list));
			}

			self.editList = function(list) {
				goTo("editList");
				subGoTo("editListItem");
			//location.hash = list.id();
		}

		self.goToList = function(list) {
			location.hash = "lists/"+list.id();
		}

		self.editListItem = function(listItem) {

			self.selectedListItem(listItem);
			goTo("editSpecificItem");        
			perform("commit", self.list);
			
		}

		self.removeListItem = function(listItem) {
			var id = listItem.id();
			if(id > 0) {
				self.selectedListItem(listItem);
				listItem.status("deleted");
				perform("commit", self.selectedListItem);	
			} else {
				self.list().items.remove(listItem);
			}
			
			console.log(ko.toJS(self.list))
		}

		self.saveList = function() {
			perform("commit", self.list);
			goTo("displayList");

			var queue = ListForksInstance.postQueue;
			var rawList = ko.toJS(self.list);
			delete rawList.filteredItems;
			
			var id = rawList.id;

			var message = {};

			if(id >= 1) { //PUT
				message.type = "put";
				message.id = id;        
			} else { //NEW ITEM
				message.type = "post";
				delete rawList.id;
			}


			var restfulData = {
				userId: window.userAccountId(),
				list: rawList
			};

			message.data = ko.toJSON(restfulData);
			message.module = "lists";
			self.parent().remove(self, true);

			queue(message);
		}

		self.cancelList = function(list) {
			if(list.id() <= 0) {
				self.parent().remove(self, true);
			} else {
				goTo("displayList");
				subGoTo("displayListItem");
				perform("reset", self.list);  
			}
		}

		self.saveListItem = function() {
			self.editList();
			if(self.selectedListItem().id() > 0) {
				self.selectedListItem().status("updated");
			} else {
				self.selectedListItem().status("new");
			}
			perform("commit", self.selectedListItem);
		}

		self.cancelListItem = function() {
			self.editList()
			perform("reset", self.selectedListItem);
		}

		self.init = function(data) {
			data = data || {};
			self.id(data.id || -1);     
			self.list(new List(data));  
			return self;
		}

		return self.init(data);
	}

	var self = {};

	self.getContainer = ko.observableArray([]);  
	self.hasGeolocation = ko.observable();
	self.canDrawMap = ko.observable(false);
	self.lists = ko.observableArray([]);
	self.list = ko.observable();

	self.getContainer.subscribe(function(data){

		if($.isArray(data)) { // GET []

			var lists = ko.utils.arrayMap(data, function(rawList) {
				return new ListGUI(rawList.attributes, self);
			});
			self.lists.push.apply(self.lists, lists);

		} else { // GET {}  

		if(data.moduleCalled === "lists") {
			self.canDrawMap(false);
		} else {
			self.canDrawMap(true);
		}

		var list = new ListGUI(data.attributes, self);
		self.list(list)
		self.lists.push(list);

	}

});

	self.list.subscribe(function(guiList) {
		var rawList = guiList.list();
		console.log(rawList);
		if(rawList.location() && rawList.location().latitude != 0 && rawList.location().longitude != 0) {
			self.hasGeolocation(true);

			var queue = ListForksInstance.actionQueue;
			var lat = rawList.location().latitude;
			var lng = rawList.location().longitude;

			if(self.canDrawMap()) {
				var message = {};
				message.method = self.drawMap;
				message.context = self;
				message.args = [lat, lng];
				queue(message);	
			}
			

		} else {
			self.hasGeolocation(false);
		}
	})

	self.drawMap = function(lat, lng) {
			var mapcanvas = document.createElement('div');
			mapcanvas.id = 'google-map';
			mapcanvas.style.height = '200px';
			mapcanvas.style.width = '100%';

			document.getElementById('google-map-container').appendChild(mapcanvas);

			var latlng = new google.maps.LatLng(lat, lng);
			var options = {
				zoom: 15,
				center: latlng,
				mapTypeControl: false,
				navigationControlOptions: {style: google.maps.NavigationControlStyle.SMALL},
				mapTypeId: google.maps.MapTypeId.ROADMAP
			};

			var map = new google.maps.Map(document.getElementById("google-map"), options);
			var marker = new google.maps.Marker({position: latlng, map: map, title: "Located here"});
	}

	self.loadGeolocation = function() {
		if (navigator.geolocation) {
			navigator.geolocation.getCurrentPosition(function(position) {
				var lat = position.coords.latitude;
				var lng = position.coords.longitude;
				self.drawMap(lat, lng);
				self.hasGeolocation(true);

				var guiList = self.list();
				var rawList = guiList.list();
				
				rawList.location({latitude: lat, longitude: lng});

				guiList.list(rawList);
				guiList.saveList();				
			
			}, function(error) {
				if(error == 1) alert("We require your permission for accessing your location. Please refresh and try again.");
				if(error == 2) alert("We are unable to find your location.");
				if(error == 3) alert("The browse Geolocation module timed out. Please refresh and try again");
			});
		} else {
			alert("We are sorry, your browser doesn't support Geolocation.");
		}
	}

	self.add = function() {
		var newlyAdded =  ko.utils.arrayFilter(self.lists(), function(list) {
			return list.id() <= 0;
		})
		if(newlyAdded.length == 0) {
			var list = new ListGUI({}, self);
			list.editList();
			self.lists.push(list);
		} else {
			alert("You already created a new list. Please fill it's contents.");
		}
	}

	self.remove = function(list, bypass) {
		var confirm; var databaseRemove = false;
		if(bypass !== true) {
			confirm = window.confirm("Are you sure you want to delete this List?");  
			databaseRemove = true;
		} else {
			confirm = 1;
		}

		if(confirm) {
			var listId = list.id();
			self.lists.remove(function(list) { return list.id() == listId });
		}

		if(databaseRemove) {
			var queue = ListForksInstance.postQueue;
			var rawList = ko.toJS(list);
			var id = rawList.id;

			var message = {type: "delete", id: id};

			var restfulData = {};

			message.data = ko.toJSON(restfulData);
			message.module = "lists";
			queue(message);
		}
	}

	self.init = function() {
		return self;
	}

	return self.init();
}



return l;

})(Listforks || {})