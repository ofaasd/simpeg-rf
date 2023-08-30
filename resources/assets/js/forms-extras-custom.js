/**
 * Form Extras
 */

'use strict';

// bootstrap-maxlength & repeater (jquery)
$(function () {
  var maxlengthInput = $('.bootstrap-maxlength-example'),
    formRepeater = $('.form-repeater');

  // Bootstrap Max Length
  // --------------------------------------------------------------------
  if (maxlengthInput.length) {
    maxlengthInput.each(function () {
      $(this).maxlength({
        warningClass: 'label label-success bg-success text-white',
        limitReachedClass: 'label label-danger',
        separator: ' out of ',
        preText: 'You typed ',
        postText: ' chars available.',
        validate: true,
        threshold: +this.getAttribute('maxlength')
      });
    });
  }

  // Form Repeater
  // ! Using jQuery each loop to add dynamic id and class for inputs. You may need to improve it based on form fields.
  // -----------------------------------------------------------------------------------------------------------------

  if (formRepeater.length) {
    var row = 2;
    var col = 1;
    formRepeater.on('submit', function (e) {
      e.preventDefault();
    });
    const url_location = $('#location').val();
    formRepeater.repeater({
      show: function () {
        var fromControl = $(this).find('.form-control, .form-select');

        var formLabel = $(this).find('.form-label');

        fromControl.each(function (i) {
          var id = 'form-repeater-' + row + '-' + col;
          $(fromControl[i]).attr('id', id);
          $(formLabel[i]).attr('for', id);
          col++;
        });

        row++;

        $(this).slideDown();
        $('.select2-container').remove();
        $('.select2').select2({
          placeholder: '--Pilihan--',
          allowClear: true
        });
        $('.select2-container').css('width', '100%');
      },
      hide: function (e) {
        if (confirm('Are you sure you want to delete this element?') == true) {
          if ($(this).find('.id_key').val() == 0) $(this).slideUp(e);
          else {
            $.ajax({
              data: { id: $(this).find('.id_key').val() },
              url: ''.concat(baseUrl).concat('pegawai/', url_location),
              type: 'POST',
              success: function success(status) {
                // sweetalert

                Swal.fire({
                  icon: 'success',
                  title: 'Successfully Deleted',
                  text: ''.concat('Record ', ' ').concat(status + ' Successfully.'),
                  customClass: {
                    confirmButton: 'btn btn-success'
                  }
                });
              },
              error: function error(err) {
                Swal.fire({
                  title: 'Record Error',
                  text: title + ' Not Saved !',
                  icon: 'error',
                  customClass: {
                    confirmButton: 'btn btn-success'
                  }
                });
              }
            });
            $(this).slideUp(e);
          }
        }
      }
    });
  }
});
