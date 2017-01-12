(function(tinymce, $) {
  'use strict';

  var LOCALE = 'fi';
  var searching = false;
  var pending = false;

  function loadPendingElements(context){
    var tinyMceDom = tinymce.dom.DomQuery;
    tinyMceDom('.mce-kunta-api-component-load-pending', context).each(function(index, element){
      var component = tinyMceDom(element, context).attr('data-component-type');
      var serviceId = tinyMceDom(element, context).attr('data-service-id');
      $.post( ajaxurl, {
        'action': 'kunta_api_render_service_component',
        'serviceId': serviceId,
        'lang': LOCALE,
        'component': component,
      }, function(response){
        var newElement = tinyMceDom(response, context);
        tinyMceDom(element, context).replaceWith(newElement);
        tinyMceDom('article[data-type="kunta-api-service-component"]', context).addClass('mceNonEditable');
      });
    }); 
  }

  function searchServices(query, callback) {
    $.post( ajaxurl, {
      'action': 'kunta_api_search_services',
      'data': query
    }, function(response){
      callback(JSON.parse(response));
    });
  }
  
  function getLocalizedValueAndType(values, locale, type) {
    for(var i = 0; i < values.length; i++) {
      if(locale == values[i].language && type == values[i].type) {
        return values[i].value;
      }
    }
    return null;
  }
  
  function appendResult(result) {
    var resultContainer = $('<div>').addClass('mce-kunta-api-search-result-row');
    var languages = result.languages;
    var name = getLocalizedValueAndType(result.names, LOCALE, 'Name');
    var userInstruction = getLocalizedValueAndType(result.descriptions, LOCALE, 'ServiceUserInstruction');
    var description = getLocalizedValueAndType(result.descriptions, LOCALE, 'Description');
    resultContainer.append(
      $('<p>')
        .addClass('mce-kunta-api-search-result-title')
        .text(name)
    );
    resultContainer.append(
      $('<p>')
        .append($('<input>')
        .addClass('service-component-embed-input')
        .attr({
          'type':'checkbox',
          'data-component-type': 'description',
          'data-service-id': result.id
        }))
        .append(
          $('<span>')
            .text('kuvaus')
            .attr('title', description))
    );
    
    resultContainer.append(
      $('<p>')
        .append($('<input>')
        .addClass('service-component-embed-input')
        .attr({
          'type':'checkbox',
          'data-component-type': 'userInstruction',
          'data-service-id': result.id
        }))
        .append(
          $('<span>')
            .text('Toimintaohjeet')
            .attr('title', userInstruction))
    );
    
    resultContainer.append(
      $('<p>')
        .append($('<input>')
        .addClass('service-component-embed-input')
        .attr({
          'type':'checkbox',
          'data-component-type': 'languages',
          'data-service-id': result.id
        }))
        .append(
          $('<span>')
            .text('Kielet, joilla palvelu on saatavilla')
            .attr('title', languages.join(',')))
    );

    $('.mce-kunta-api-search-results').append(resultContainer);
  }
  
  function handleResponse(response) {
    $('.mce-kunta-api-search-results').empty();
    for(var i = 0; i < response.length; i++) {
      appendResult(response[i]);
    }
  }

  tinymce.PluginManager.add('kunta_api_service_embed', function(editor, url) {

    editor.addButton('kunta_api_service_embed', {
      title: 'Search Kunta API services',
      onclick: function() {
        editor.windowManager.open({
          title: 'Search Kunta API services',
          width: 768,
          height: 500,
          body: [
            {type: 'textbox', name: 'kunta-api-service-query', label: 'Query', onKeyUp: function(e) {     
              if(!searching) {
                searching = true;
                searchServices(e.target.value, function(res){
                  searching = false;
                  if (pending) {
                    searchServices(e.target.value, handleResponse);
                  } else {
                    handleResponse(res);
                  }
                });
              } else {
                pending = true;
              }
            }},
            {type: 'container', classes: 'kunta-api-search-results', minHeight: 420}
          ],
          onsubmit: function(e) {
            var componentsToEmbed = $('.service-component-embed-input:checked');
            var responseHtml = '';
            componentsToEmbed.each(function(){
              var component = $(this).attr('data-component-type');
              var serviceId = $(this).attr('data-service-id');
              responseHtml += $('<article>')
                .addClass('mce-kunta-api-component-load-pending mceNonEditable')
                .attr({'data-component-type': component, 'data-service-id': serviceId})
                .text('Ladataan...')
                .prop('outerHTML');
            });
            editor.insertContent(responseHtml);
            loadPendingElements(editor.getDoc());
          }
        });
      }
    });
  });
})(tinymce, jQuery);
