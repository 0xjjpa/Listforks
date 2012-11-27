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
      data = data || {};
      var self = {};
      self.id = ko.protectedObservable(data.id || -1);
      self.description = ko.protectedObservable(data.description || "Item Description");
      return self;
    }

    var List = function(data) {
      data = data || {};
      var self = {};
      self.id = ko.protectedObservable(data.id || -1);
      self.name = ko.protectedObservable(data.name || "List Name");
      self.description = ko.protectedObservable(data.description || "List Description");
      self.private = ko.protectedObservable(data.private || 0);
      self.location = ko.protectedObservable(data.location || {});
      self.rating = ko.protectedObservable(data.rating || 0);

      self.items = ko.observableArray([]);

      var items = data.items || [];
      var newItems = ko.utils.arrayMap(items, function(rawItems) {
        return new ListItem(rawItems);
      });

      self.items.push.apply(self.items, newItems);
      return self;
    }

    var ListGUI = function(data) {
      var self = {};
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

    self.editListItem = function(listItem) {
      self.selectedListItem(listItem);
      goTo("editSpecificItem");        
    }

    self.saveList = function() {
      goTo("displayList");
      perform("commit", self.list);
    }

    self.cancelList = function() {
      goTo("displayList");
      subGoTo("displayListItem");
      perform("reset", self.list);
    }

    self.saveListItem = function() {
      self.editList();
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
  self.lists = ko.observableArray([]);

  self.getContainer.subscribe(function(data){
    var lists = ko.utils.arrayMap(data, function(rawList) {
      return new ListGUI(rawList.attributes);
    });

    self.lists.push.apply(self.lists, lists);
  });

  self.add = function() {
    var list = new ListGUI();
    list.editList();
    self.lists.push(list);
  }

  self.remove = function(list) {
    var confirm = window.confirm("Are you sure you want to delete this List?");
    if(confirm) {
      var listId = list.id();
      self.lists.remove(function(list) { return list.id() == listId });
    }
  }

  self.init = function() {
    return self;
  }

  return self.init();
}



return l;

})(Listforks || {})