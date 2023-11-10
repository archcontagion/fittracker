/**
 * First we will load all of this project's JavaScript dependencies which
 * include Vue and Vue Resource. This gives a great starting point for
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./vendor/jquery.min.js');
require('./vendor/moment.js');
require('./vendor/bootstrap.js');
require('./vendor/bootstrap-datetimepicker.js');
require('./vendor/bootstrap-timepicker.js');
require('./vendor/daterangepicker.js');
require('./vendor/Chart.min.js');
require('./vendor/fullcalendar.js');

String.prototype.toMinutes = function () {
  var minutes, darray, duration = this.toString();

  darray = duration.split(':')
  minutes = (darray[0] * 60) + darray[1];
  return minutes;
}


var trackerModules = trackerModules || {};

trackerModules.Notifier = (function () {
  'use strict';



  return {
    createNote: function (selector, str) {

      $(selector).on('click', function (e) {
        e.preventDefault();
        var self = this;
        BootstrapDialog.show({
          message: str,
          buttons: [{
            label: 'yes',
            action: function (dialog) {
              $.get($(self).attr('href'));
              $(self).parents('li').remove();
              dialog.close();
            }
          }, {
            label: 'no',
            action: function (dialog) {
              dialog.close();
            }
          }]
        });

      });

    },

    init: function (msgs) {

      var self = this,
        json = null;

      for (var i = 0; i < msgs.length; i++) {
        json = msgs[i];
        self.createNote(json.selector, json.note);
      }
    }
  };
})();



trackerModules.configureFormMask = function (s) {
  switch (s) {

  case 'Body':
    $('.body').show();
    $('.weight,.cardio,.static').not('.body').hide();
    break;
  case 'Weight':
    $('.weight').show();
    $('.body,.cardio,.static').not('.weight').hide();
    break;
  case 'Cardio':
    $('.cardio').show();
    $('.body,.weight,.static').not('.cardio').hide();
    break;
  case 'Static':
    $('.static').show();
    $('.body,.weight,.cardio').not('.static').hide();
  }
}



$(function () {

  $('table[data-form="deleteForm"]').on('click', '.form-delete', function (e) {

    var $form = $(this);
    $('#confirm').modal({
        backdrop: 'static',
        keyboard: false
      })
      .on('click', '#delete-btn', function () {
        $form.submit();
      });
  });

  $('.exercisestatsModal').on('click', function (e) {
    $('iframe', '#statsModal').attr('src', $(this).attr('data-href'));
  });
  // reinit dropdowns cause of dynamic html load
  $('#statsModal').on('shown.bs.modal', function (e) {
    $("[data-toggle='dropdown']").dropdown();
  });

  $('#confirmModal').on('show.bs.modal', function (e) {
    $(this).find('.btn-danger').attr('href', $(e.relatedTarget).data('href'));
    $(this).find('.btn-danger').on('click', function (e) {
      window.location.href = $(this).attr('href');
    });
  });

  $('#importModal').on('show.bs.modal', function (e) {
    $(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));
    $(this).find('.btn-ok').on('click', function (e) {
      window.location.href = $(this).attr('href');
    });
  });

  $('.date').datetimepicker({
    format: 'DD.MM.YYYY HH:mm:ss',
    defaultDate: $('.datefield').attr('value')
  });

  jQuery(document).ready(function ($) {
    $('#tabs').tab();
  });

  $('.durationfield').each(function () {

    $(this).timepicker({
      'template': 'dropdown',
      'showMeridian': false,
      'defaultTime': false,
      'showSeconds': true,
      'minuteStep': 1,
      'secondStep': 1,
      'maxHours': 24,
      'format': 'hh:mm:ss'
    });
  });

  $('.daterange').daterangepicker({
    locale: {
      format: 'DD.MM.YYYY'
    }
  });

  var url = window.location.href;

  var daterange = null;
  if (url.match(/daterange=.+/) != null) {
    if (url.match(/daterange=.+&/) == null) {
      daterange = url.match(/daterange=.+/);
      daterange = daterange[0].substring(daterange[0].indexOf('daterange=') + 10, daterange[0].length);
    } else {
      daterange = url.match(/daterange=.+&/);
      daterange = daterange[0].substring(daterange[0].indexOf('daterange=') + 10, daterange[0].indexOf('&'));
    }
  }

  if (daterange == null || daterange == '') {
    $('.daterange').val('');
  } else {
    daterange = daterange.replace(/\+/g, ' ');
    $('.daterange').val(daterange);
  }

  $('.resetFilter').on('click', function () {
    $('.daterange,#compare_user_id').val('');
  });

  trackerModules.configureFormMask($(':selected', '.exercisetype_select').attr('data-type'));
  $('.exercisetype_select').on('change', function () {
    trackerModules.configureFormMask($(':selected', '.exercisetype_select').attr('data-type'));
  });


});
