(function ($) {

  function setMetaformPage(metaform, page, lastPageCallback) {
    var pageInput = $(metaform).find('input[name="page"]');
    var max = parseInt(metaform.find('input[name="page-count"]').val());
    var page = Math.min(Math.max((parseInt(page) || 0), 0), max);
    if (page >= max) {
      lastPageCallback();
    } else {
      pageInput.val(page).change();
      metaform.closest('.metaform-container').find('.metaform-page').text(page + 1);
    }
  }

  function getMetaformPage(metaform) {
    return parseInt($(metaform).find('input[name="page"]').val()) || 0;
  }

  function getMetaformPageCount(metaform) {
    return parseInt($(metaform).find('input[name="page-count"]').val()) || 0;
  }

  function changeMetaformPage(metaform, delta) {
    var metaformContainer = $(metaform).closest('.metaform-container');
    var id = metaformContainer.attr('data-id');
    $(metaform).metaform('option', 'animation.hide.options.direction', delta > 0 ? 'left' : 'right');
    $(metaform).metaform('option', 'animation.show.options.direction', delta > 0 ? 'right' : 'left');
    
    setMetaformPage(metaform, getMetaformPage(metaform) + delta, function () {
      metaformContainer.addClass('saving');
      saveMetaform(metaform, function (saveErr) {
        if (saveErr) {
          alert(saveErr);
        } else {
          saveMetaformRevision(metaform, function (err) {
            if (err) {
              alert(err);
            } else {
              window.location = '/results?query_id=' + id;
            }
          });    
        }
      });     
    });
  }

  function saveMetaformRevision(metaform, callback) {
    var id = $(metaform).closest('.metaform-container').attr('data-id');
    var ajaxurl = metaformwp.ajaxurl;

    $.post(ajaxurl, {
      'action': 'save_metaform_revision',
      'id': id
    }, function (response) { 
      callback(null);
    })
    .fail(function (response) {
      callback(response.responseText || response.statusText || "Unknown error occurred");
    });
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

  function createPagedMetaform(metaform) {
    var pageCount = getMetaformPageCount(metaform);
    var pages = $('<div class="row"><div class="col text-center"><div class="metaform-pages-container"><span class="metaform-page">1</span><span>/</span><span class="metaform-pages">' + pageCount + '</span></div></div></div>');
    var navigation = $('<div class="row"><div class="col text-right"><a class="metaform-prev" href="#"><i class="fa fa-arrow-left" aria-hidden="true"></i><span>Edellinen</span></a></div><div class="col text-left"><a class="metaform-next" href="#"><span>Seuraava</span><i class="fa fa-arrow-right" aria-hidden="true"></i></a></div></div><input type="hidden" name="page-count" value="' + pageCount + '"/>');
    
    metaform.closest('.metaform-container')
      .prepend(pages)
      .append(navigation);

    var valuesArray = metaform.metaform('val', true);
    for (var i = 0; i < valuesArray.length; i++) {
      var name = valuesArray[i].name;
      if (name === 'page') {
        var value = valuesArray[i].value;
        setMetaformPage(metaform, value);
      }
    }
    
    $(metaform).metaform('option', 'animation', {
      framework: 'jquery-ui',
      hide: {
        effect: 'slide',
        duration: 400,
        options: {
          direction: 'left'
        }
      },
      show: {
        effect: 'slide',
        duration: 400,
        options: {
          direction: 'right'
        }
      }
    });

    $(document).on('click', '.saving .form-check-input', function (event) {
      event.preventDefault();
    });

    $(document).on('click', '.saving .form-check-input', function (event) {
      event.preventDefault();
    });

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
      var metaformContainer = $(input).closest('.metaform-container');
      metaformContainer.addClass('saving');

      saveMetaform(metaform, function (err) {
        metaformContainer.removeClass('saving');
        
        if (err) {
          alert(err);
        }
      });
    });
  }

  function updateCompositeFields() {
    var desiredUsage = 0;
    var currentUsage = 0;

    $('.service-usage-desired').each(function(index, element) {
      desiredUsage += $(element).find('.ui-slider').slider('value');
    });

    $('.service-usage-current').each(function(index, element) {
      currentUsage += $(element).find('.ui-slider').slider('value');
    });

    $('.current-total').text(currentUsage);
    var currentPercentage = currentUsage - 100;
    var currentPercentageText = currentPercentage >= 0 ? "enemmän" : "vähemmän";
    $('.current-total-percentage').text(Math.abs(currentPercentage) + ' % ' + currentPercentageText);

    $('.desired-total').text(desiredUsage);
    var desiredPercentage = desiredUsage - 100;
    var desiredPercentageText = desiredPercentage >= 0 ? "enemmän" : "vähemmän";
    $('.desired-total-percentage').text(Math.abs(desiredPercentage) + ' % ' + desiredPercentageText);
  }

  $(document).on('metaformcreate', function (event, ui) {
    var metaform = $(event.target);

    if (metaform.closest('.metaform-paged').length) {
      createPagedMetaform(metaform);
    }

    metaform.find('.slider input[type="number"]').each(function (index, input) {
      $(input).hide();
      var handleText = null;

      $('<div>')
        .appendTo($(input).parent())
        .slider({
          value: $(input).val() || 0,
          min: parseInt($(input).attr('min')) || 0,
          max: parseInt($(input).attr('max')) || 100,
          create: function() {
            handleText = $('<span>')
              .addClass('slider-handle-text')
              .appendTo($(this).find('.ui-slider-handle'))
              .text($( this ).slider("value"));
          },
          slide: function(event, ui) {
            $(input).attr('value', ui.value);
            handleText.text(ui.value);
          },
          change: function( event, ui ) {
            updateCompositeFields();
          }
        });
    });

    updateCompositeFields();
  });

  $(document).on('click', 'input[type="submit"]', function (event) {
    var button = $(event.target);
    var metaform = button.closest('.metaform-container').find('.metaform');
    var valid = typeof metaform[0].checkValidity === 'function' ? metaform[0].checkValidity() : true;

    if (valid) {
      event.preventDefault();
      saveMetaform(metaform, function (err) {
        if (err) {
          alert(err);
        } else {
          var saveRedirect = metaform.find('input[name="save-redirect"]').val();
          if (saveRedirect) {
            window.location.href = saveRedirect;
          }
        }
      });
    }
  });

  $(document).on('click', '.metaform-next', function (event) {
    event.preventDefault();
    var link = $(event.target);
    if (link.closest('.saving').length) {
      return;
    }

    changeMetaformPage(link.closest('.metaform-container').find('.metaform'), +1);
  });

  $(document).on('click', '.metaform-prev', function (event) {
    event.preventDefault();
    var link = $(event.target);
    if (link.closest('.saving').length) {
      return;
    }
    
    changeMetaformPage(link.closest('.metaform-container').find('.metaform'), -1);
  });

  $(document).ready(function () {
    $('#metaform-averages').each(function (index, element) {
      var ctx = element.getContext('2d');
      var values = JSON.parse($(element).attr('data-values'));
      var smallScreen = $( window ).width() < 992 ? true : false;
      var options = {
        legend: {
          position: smallScreen ? 'top' : 'right'
        },
        scales: {
          xAxes: [{
            position: 'top',
            ticks: {
              max: 5,
              min: 0,
              stepSize: 1,
              fontStyle: 'bold',
              fontColor: '#000',
              fontSize: 16
            }
          }],
          yAxes: [{
            ticks: {
              fontStyle: 'bold',
              fontColor: '#000',
              fontSize: smallScreen ? 10 : 16,
              display: true,
              callback: function(value, index, values) {
                if (smallScreen && value.length > 15) {
                  return value.substring(0, 12).toUpperCase() + '...';
                }

                return value.toUpperCase();
              }
            }
          }]
        }
      };
      var labels = values.categories;
      var datasUserAverages = [];
      var datasAverages = [];

      $.each(labels, function (index, label) {
        datasUserAverages.push(parseFloat(values.userAverages[label]) || 0);
        datasAverages.push(parseFloat(values.averages[label]) || 0);
      });

      var chart = new Chart(ctx, {
        type: 'horizontalBar',
        data: {
          labels: labels,
          datasets: [{
            label: 'Sinä',
            data: datasUserAverages,
            backgroundColor: "#039fd2"
          }, {
            label: 'Keskiarvo',
            data: datasAverages,
            backgroundColor: "#000"
          }]
        },
        options: options
      });
    });
  });

})(jQuery);
