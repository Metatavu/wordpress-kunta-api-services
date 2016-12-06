(function() {
  
  var LOCALE = 'fi';
  
  var AjaxLoader = CKEDITOR.tools.createClass({
    $: function() {
      
    },
   
    proto : {
      doPost: function (url, params, callback) {
        this._doPost(url, this._queryParams(params), "application/x-www-form-urlencoded; charset=UTF-8", callback);
      },
      
      _queryParams: function (paramMap) {
        var result = [];
        var keys = Object.keys(paramMap);
        
        for (var i = 0, l = keys.length; i < l; i++) {
          var key = keys[i];
          result.push(key + '=' + paramMap[key]);
        }
        
        return result.join('&');
      },
      
      _doPost: function(url, data, contentType, callback) {
        var xhr = this._createXMLHttpRequest();
        xhr.open("POST", url, true);
        xhr.setRequestHeader("Content-type", contentType);
  
        if (!CKEDITOR.env.webkit) {
          // WebKit refuses to send these headers as unsafe
          xhr.setRequestHeader("Content-length", data ? data.length : 0);
          xhr.setRequestHeader("Connection", "close");
        }
  
        xhr.onreadystatechange = function() {
          if (xhr.readyState === 4) {
            callback(xhr.status, xhr.responseText);
          }
        };
        
        xhr.send(data);
      },
      
      _createXMLHttpRequest: function() {
        try { return new XMLHttpRequest(); } catch(e) {}
        try { return new ActiveXObject( 'Msxml2.XMLHTTP' ); } catch (e) {}
        try { return new ActiveXObject( 'Microsoft.XMLHTTP' ); } catch (e) {}
        return null;
      }
    }
  });
  
  var ServiceSearcher = CKEDITOR.tools.createClass({
    $: function() {
      this._searching = false;
      this._pending = null;
      this._cancelled = false;
    },
    
    proto : {
      search: function (query, callback) {
        this._cancelled = false;
        
        if (!this._searching) {
          this._searching = true;
          
          this._search(query, CKEDITOR.tools.bind(function (status, response) {
            this._searching = false;
            if (this._pending) {
              var pendingQuery = this._pending;
              this._pending = null;
              this._search(pendingQuery, CKEDITOR.tools.bind(function (pendingStatus, pendingResponse) {
                if (!this._cancelled) {
                  this._handleResponse(pendingStatus, pendingResponse, callback);
                }
              }, this));
            } else {
              if (!this._cancelled) {
                this._handleResponse(status, response, callback);
              }
            }
            
          }, this));
          
        } else {
          this._pending = query;
        }
      },
      
      cancel: function () {
        this._cancelled = true;
      },
      
      _handleResponse: function (status, response, callback) {
        if (status >= 200 && status <= 299) {
          callback(null, JSON.parse(response));
        } else {
          callback(response, null);
        }
      },
      
      _search: function (query, callback) {
        var ajaxLoader = new AjaxLoader();
        var params = {
          "action": "kunta_api_search_services",
          "data": query
        };
        ajaxLoader.doPost(ajaxurl, params, callback);
      }
    }
  });
  
  var ResultHandler = CKEDITOR.tools.createClass({
    $: function(dialog) {
      this._dialog = dialog;
    },
    
    proto : {
      
      getSelectedComponents: function () {
        var results = [];
        
        var inputs = this._findContainer()
          .find('.service-component-embed-input:checked');
        
        for (var i = 0, l = inputs.count(); i < l; i++) {
          var input = inputs.getItem(i);
          results.push({
            type: input.getAttribute('data-component-type'),
            serviceId: input.getAttribute('data-service-id')
          });
        }
        
        return results;
      },
      
      renderComponent: function (component) {
        var html = 
          '<img style="vertical-align: top; padding-right: 8px; margin-top: 2px" src="/wp-admin/images/loading.gif"/>' + 
          '<span>Lataa...</span>';
        
        var article = new CKEDITOR.dom.element('article');
        article.setAttribute('data-type', 'kunta-api-service-component');
        article.setAttribute('data-component', component.type);
        article.setAttribute('data-service-id', component.serviceId);
        article.setAttribute('contenteditable', 'false');
        article.setAttribute('readonly', 'true');
        article.setHtml(html);
        article.addClass('kunta-api-component-load-pending');
        return article.getOuterHtml();
      },
      
      loadPending: function (document) {
        var ajaxLoader = new AjaxLoader();
        
        var pendingElements = document.find('.kunta-api-component-load-pending');
        
        for (var i = 0, l = pendingElements.count(); i < l; i++) {
          var pendingElement = pendingElements.getItem(i);
          var component = pendingElement.getAttribute('data-component');
          var serviceId = pendingElement.getAttribute('data-service-id');
          var params = {
            'action': 'kunta_api_render_service_component',
            'serviceId': serviceId,
            'lang': LOCALE,
            'component': component
          };
          
          ajaxLoader.doPost(ajaxurl, params, CKEDITOR.tools.bind(function (status, response) {
            if (status >= 200 && status <= 299) {
              this.hide();
              
              var element = new CKEDITOR.dom.element('pre');
              element.setHtml(response);
              var children = element.getChildren();
              for (var i = children.count() - 1; i >= 0; i--) {
                var child = children.getItem(i);
                
                if ((child.type == CKEDITOR.NODE_ELEMENT) && child.getAttribute('data-type') == 'kunta-api-service-component') {
                  child.setAttribute('contenteditable', false);
                  child.setAttribute('readonly', true);
                }
                
                child.insertAfter(this);
              }
              
              this.remove();
            } else {
              this.setHtml("Lataus epäonnistui. Virhe: " + response);
            }
          }, pendingElement));
        }
      },
      
      _findContainer: function () {
        return this._dialog
          .getContentElement("embed", "results")
          .getInputElement();
      }
    }
    
  });
  
  var ResponseRenderer = CKEDITOR.tools.createClass({
    $: function(dialog) {
      this._dialog = dialog;
    },
    
    proto : {
      
      renderHelp: function () {
        this._findContainer()
          .setHtml(this._createHelpHtml());
      },
     
      renderLoader: function () {
         this._findContainer()
           .setHtml(this._createLoaderHtml());
      },
      
      renderError: function (message) {
        this._findContainer()
          .setHtml(this._createErrorHtml(message));
      },
      
      renderServices: function (services) {
        if (services && services.length > 0) {
          var html = '';
        
          for (var i = 0, l = services.length; i < l; i++) {
            html += this._createServiceHtml(services[i]);
          }
        
          this._findContainer()
            .setHtml(html);
        } else {
          this._findContainer()
            .setHtml(this._createNoResults());
        }
      },
      
      _getLocalizedValueAndType: function (values, locale, type) {
        for(var i = 0; i < values.length; i++) {
          if(locale == values[i].language && type == values[i].type) {
            return values[i].value;
          }
        }
        return null;
      },
      
      _createServiceHtml: function (service) {
        var languages = service.languages;
        var name = this._getLocalizedValueAndType(service.names, LOCALE, 'Name');
        var userInstruction = this._getLocalizedValueAndType(service.descriptions, LOCALE, 'ServiceUserInstruction');
        var description = this._getLocalizedValueAndType(service.descriptions, LOCALE, 'Description');
        
        var serviceContainer = new CKEDITOR.dom.element('div');
        serviceContainer.setStyles({
          'margin-bottom': '10px',
          'margin-right': '10px',
          'border': '1px solid #aaa',
          'padding': '5px',
          'border-radius': '5px'
        });
        
        var serviceName = new CKEDITOR.dom.element('p');
        serviceName.setStyles({
          'font-weight': 'bold',
          'margin-bottom': '5px'
        });
        serviceName.setHtml(name);
        
        serviceContainer.append(serviceName);
        
        if (description) {
          serviceContainer.append(this._createCheckboxField(service.id, 'description', 'Kuvaus', description));
        }
        
        if (userInstruction) {
          serviceContainer.append(this._createCheckboxField(service.id, 'userInstruction', 'Toimintaohjeet', userInstruction));
        }
        
        if (languages) {
          serviceContainer.append(this._createCheckboxField(service.id, 'languages', 'Kielet, joilla palvelu on saatavilla', languages.join(',')));
        }
        
        return serviceContainer.getOuterHtml();
      },
      
      _createCheckboxField: function (serviceId, type, title, value) {
        var inputElement = new CKEDITOR.dom.element('input');
        inputElement.setAttribute('type', 'checkbox');
        inputElement.setAttribute('data-component-type', type);
        inputElement.setAttribute('data-service-id', serviceId);
        inputElement.addClass('service-component-embed-input');
        
        var titleElement = new CKEDITOR.dom.element('span');
        titleElement.setAttribute('title', value);
        titleElement.setHtml(title);
        
        var field = new CKEDITOR.dom.element('p');
        field.append(inputElement);
        field.append(titleElement);
        
        return field;
      },
      
      _createLoaderHtml: function () {
        var html =
          '<img style="vertical-align: top; padding-right: 8px; margin-top: 2px" src="/wp-admin/images/loading.gif"/>' + 
          '<span>Lataa...</span>';
        return this._wrapInTextContainer(html);
      },
      
      _createHelpHtml: function () {
        return this._wrapInTextContainer('<span>Kirjoita hakusana yllä olevaan hakukenttään</span>');
      },
      
      _createNoResults: function () {
        return this._wrapInTextContainer('<span>Hakusanalla ei löytynyt yhtään palvelua</span>');
      },
      
      _createErrorHtml: function (error) {
        var html =
          '<span>Hakiessa palveluita tapahtui virhe:</span>' +
          '<span style="color: red">' + error + '</span>';
        return this._wrapInTextContainer(html);
      },
      
      _createTextContainer: function () {
        var containerElement = new CKEDITOR.dom.element('div');
        containerElement.setStyles({
          'padding-top': '25px',
          'text-align': 'center',
          'font-size': '16px'
        });
        return containerElement;
      },
      
      _wrapInTextContainer: function (innerHtml) {
        var containerElement = this._createTextContainer();
        containerElement.setHtml(innerHtml);
        return containerElement.getOuterHtml();
      },
      
      _findContainer: function () {
        return this._dialog
          .getContentElement("embed", "results")
          .getInputElement();
      }
      
    }
  });

  CKEDITOR.dialog.add('kunta-api-service-embed', function(editor) {
    return {
      title : 'Upota palvelutietoja',
      minWidth : 768,
      minHeight : 400,
      contents : [{
        id : 'embed',
        label : 'Upota palvelutietoja',
        elements : [ {
          type : 'text',
          id : 'search',
          label : 'Haku'
        }, {
          type: 'html',
          id: 'results',
          html: '<div style="height: 340px; overflow-y: scroll"></div>'
        }]
      }],
      
      onShow: function () {
        var dialog = this;
        var renderer = new ResponseRenderer(dialog);
        var searcher = new ServiceSearcher();
        
        var searchElement = dialog
          .getContentElement("embed", "search")
          .getInputElement();
        
        renderer.renderHelp();
        
        searchElement.on("keyup", function () {
          var query = this.getValue();
          
          if (!query) {
            searcher.cancel();
            renderer.renderHelp();
          } else {
            renderer.renderLoader();
            searcher.search(query, function (err, services) {
              if (err) {
                renderer.renderError(services);
              } else {
                renderer.renderServices(services);
              }
            });
          }
        });
      },
      
      onOk: function() {
        var dialog = this;
        
        var resultHandler = new ResultHandler(dialog); 
        var components = resultHandler.getSelectedComponents();
        var html = '';
        
        for (var i = 0, l = components.length; i < l; i++) {
          var component = components[i];
          html += resultHandler.renderComponent(component);
        }
        
        editor.insertHtml(html);
        resultHandler.loadPending(editor.document);
      }
    };
  });

}).call(this);