(function ($) {

  function setMetaformPage(metaform, page) {
    var pageInput = $(metaform).find('input[name="page"]');
    var max = parseInt(metaform.find('input[name="page-count"]').val()) - 1;
    var page = Math.min(Math.max((parseInt(page) || 0), 0), max);
    pageInput.val(page);
    pageInput.change();
    metaform.closest('.metaform-container').find('.metaform-page').text(page + 1);
  }

  function getMetaformPage(metaform) {
    return parseInt($(metaform).find('input[name="page"]').val()) || 0;
  }

  function getMetaformPageCount(metaform) {
    return parseInt($(metaform).find('input[name="page-count"]').val()) || 0;
  }

  function changeMetaformPage(metaform, delta) {
    $(metaform).metaform('option', 'animation.hide.options.direction', delta > 0 ? 'left' : 'right');
    $(metaform).metaform('option', 'animation.show.options.direction', delta > 0 ? 'right' : 'left');
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

    var pageCount = getMetaformPageCount(metaform);

    var pages = $('<div class="row"><div class="col text-center"><div class="metaform-pages-container"><span class="metaform-page">1</span><span>/</span><span class="metaform-pages">' + pageCount + '</span></div></div></div>');
    var navigation = $('<div class="row"><div class="col text-right"><a class="metaform-prev" href="#"><i class="fa fa-arrow-left" aria-hidden="true"></i><span>Edellinen</span></a></div><div class="col text-left"><a class="metaform-next" href="#"><span>Seuraava</span><i class="fa fa-arrow-right" aria-hidden="true"></i></a></div></div><input type="hidden" name="page-count" value="' + pageCount + '"/>');
    
    metaform.closest('.metaform-container')
      .prepend(pages)
      .append(navigation);

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
    changeMetaformPage(link.closest('.metaform-container').find('.metaform'), +1);
  });

  $(document).on('click', '.metaform-prev', function (event) {
    event.preventDefault();
    var link = $(event.target);
    changeMetaformPage(link.closest('.metaform-container').find('.metaform'), -1);
  });

})(jQuery);