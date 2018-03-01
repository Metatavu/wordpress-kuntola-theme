(function ($) {

  function setMetaformPage(metaform, page) {
    var pageInput = $(metaform).find('input[name="page"]');
    var max = parseInt(metaform.find('input[name="page-count"]').val()) - 1;
    var page = Math.min(Math.max((parseInt(page) || 0), 0), max);
    pageInput.val(page);
    pageInput.change();
    metaform.find('.metaform-page').text(page + 1);
  }

  function getMetaformPage(metaform) {
    return parseInt($(metaform).find('input[name="page"]').val()) || 0;
  }

  function changeMetaformPage(metaform, delta) {
    setMetaformPage(metaform, getMetaformPage(metaform) + delta);
  }

  function saveMetaform(metaform, callback) {
    var valuesArray = metaform.metaform('val', true); 
    var id = metaform.closest('.metaform-container').attr('data-id');
    var ajaxurl = metaformwp.ajaxurl;
    var values = {};

    for (var i = 0; i < valuesArray.length; i++) {
      var name = valuesArray[i].name;
      var value = valuesArray[i].value;
      values[name] = value;
    }

    $.post(ajaxurl, {
      'action': 'save_metaform',
      'id': id,
      'values': JSON.stringify(values)
    }, function (response) { 
      callback(null);
    })
    .fail(function (response) {
      callback(response.responseText || response.statusText || "Unknown error occurred");
    });
  }

  $(document).on('metaformcreate', function (event, ui) {
    var metaform = $(event.target);
    var valuesArray = metaform.metaform('val', true);
    for (var i = 0; i < valuesArray.length; i++) {
      var name = valuesArray[i].name;
      if (name === 'page') {
        var value = valuesArray[i].value;
        setMetaformPage(metaform, value);
      }
    }

    $(document).on('change', '.form-check-input', function (event) {
      event.preventDefault();
      var input = $(event.target);
      var metaform = input.closest('.metaform');
      changeMetaformPage(metaform, 1);
    });

    $(document).on('change', 'input[name="page"]', function (event) {
      event.preventDefault();
      var input = $(event.target);
      var metaform = input.closest('.metaform');
      
      saveMetaform(metaform, function (err) {
        if (err) {
          alert(err);
        }
      });
    });
    
  });

  $(document).on('click', '.metaform-next', function (event) {
    event.preventDefault();
    var link = $(event.target);
    changeMetaformPage(link.closest('.metaform'), +1);
  });

  $(document).on('click', '.metaform-prev', function (event) {
    event.preventDefault();
    var link = $(event.target);
    changeMetaformPage(link.closest('.metaform'), -1);
  });

})(jQuery);