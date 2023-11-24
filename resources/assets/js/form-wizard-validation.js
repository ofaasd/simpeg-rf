/**
 *  Form Wizard
 */

'use strict';

(function () {
  const select2 = $('.select2'),
    selectPicker = $('.selectpicker');

  // Wizard Validation
  // --------------------------------------------------------------------
  const wizardValidation = document.querySelector('#wizard-validation');
  if (typeof wizardValidation !== undefined && wizardValidation !== null) {
    // Wizard form
    const wizardValidationForm = wizardValidation.querySelector('#wizard-validation-form');
    // Wizard steps
    const wizardValidationFormStep1 = wizardValidationForm.querySelector('#account-details-validation');
    const wizardValidationFormStep2 = wizardValidationForm.querySelector('#personal-info-validation');
    const wizardValidationFormStep3 = wizardValidationForm.querySelector('#social-links-validation');
    // Wizard next prev button
    const wizardValidationNext = [].slice.call(wizardValidationForm.querySelectorAll('.btn-next'));
    const wizardValidationPrev = [].slice.call(wizardValidationForm.querySelectorAll('.btn-prev'));

    const validationStepper = new Stepper(wizardValidation, {
      linear: true
    });

    // Account details
    const FormValidation1 = FormValidation.formValidation(wizardValidationFormStep1, {
      fields: {
        nama_lengkap: {
          validators: {
            notEmpty: {
              message: 'Nama harap diisi'
            }
          }
        },
        tempat_lahir: {
          validators: {
            notEmpty: {
              message: 'Tempat Lahir Harap Diisi'
            }
          }
        },
        tanggal_lahir: {
          validators: {
            notEmpty: {
              message: 'Tanggal Lahir Harap Diisi'
            }
          }
        },
        alamat: {
          validators: {
            notEmpty: {
              message: 'Alamat harap diisi'
            }
          }
        },
        provinsi: {
          validators: {
            notEmpty: {
              message: 'Provinsi harap diisi'
            }
          }
        },
        kota: {
          validators: {
            notEmpty: {
              message: 'Kota harap diisi'
            }
          }
        },
        no_hp: {
          validators: {
            notEmpty: {
              message: 'No. HP Harus Diisi'
            }
          }
        },
        kode_pos: {
          validators: {
            notEmpty: {
              message: 'Kode Pos'
            }
          }
        }

        // formValidationUsername: {
        //   validators: {
        //     notEmpty: {
        //       message: 'The name is required'
        //     },
        //     stringLength: {
        //       min: 6,
        //       max: 30,
        //       message: 'The name must be more than 6 and less than 30 characters long'
        //     },
        //     regexp: {
        //       regexp: /^[a-zA-Z0-9 ]+$/,
        //       message: 'The name can only consist of alphabetical, number and space'
        //     }
        //   }
        // },
        // formValidationEmail: {
        //   validators: {
        //     notEmpty: {
        //       message: 'The Email is required'
        //     },
        //     emailAddress: {
        //       message: 'The value is not a valid email address'
        //     }
        //   }
        // },
        // formValidationPass: {
        //   validators: {
        //     notEmpty: {
        //       message: 'The password is required'
        //     }
        //   }
        // },
        // formValidationConfirmPass: {
        //   validators: {
        //     notEmpty: {
        //       message: 'The Confirm Password is required'
        //     },
        //     identical: {
        //       compare: function () {
        //         return wizardValidationFormStep1.querySelector('[name="formValidationPass"]').value;
        //       },
        //       message: 'The password and its confirm are not the same'
        //     }
        //   }
        //}
      },
      plugins: {
        trigger: new FormValidation.plugins.Trigger(),
        bootstrap5: new FormValidation.plugins.Bootstrap5({
          // Use this for enabling/changing valid/invalid class
          // eleInvalidClass: '',
          eleValidClass: '',
          rowSelector: '.col-md-12'
        }),
        autoFocus: new FormValidation.plugins.AutoFocus(),
        submitButton: new FormValidation.plugins.SubmitButton()
      },
      init: instance => {
        instance.on('plugins.message.placed', function (e) {
          //* Move the error message out of the `input-group` element
          if (e.element.parentElement.classList.contains('input-group')) {
            e.element.parentElement.insertAdjacentElement('afterend', e.messageElement);
          }
        });
      }
    }).on('core.form.valid', function () {
      // Jump to the next step when all fields in the current step are valid
      validationStepper.next();
    });

    // Personal info
    const FormValidation2 = FormValidation.formValidation(wizardValidationFormStep2, {
      // fields: {
      //   formValidationFirstName: {
      //     validators: {
      //       notEmpty: {
      //         message: 'The first name is required'
      //       }
      //     }
      //   },
      //   formValidationLastName: {
      //     validators: {
      //       notEmpty: {
      //         message: 'The last name is required'
      //       }
      //     }
      //   },
      //   formValidationCountry: {
      //     validators: {
      //       notEmpty: {
      //         message: 'The Country is required'
      //       }
      //     }
      //   },
      //   formValidationLanguage: {
      //     validators: {
      //       notEmpty: {
      //         message: 'The Languages is required'
      //       }
      //     }
      //   }
      // },
      plugins: {
        trigger: new FormValidation.plugins.Trigger(),
        bootstrap5: new FormValidation.plugins.Bootstrap5({
          // Use this for enabling/changing valid/invalid class
          // eleInvalidClass: '',
          eleValidClass: '',
          rowSelector: '.col-md-12'
        }),
        autoFocus: new FormValidation.plugins.AutoFocus(),
        submitButton: new FormValidation.plugins.SubmitButton()
      }
    }).on('core.form.valid', function () {
      // Jump to the next step when all fields in the current step are valid
      validationStepper.next();
    });

    // Bootstrap Select (i.e Language select)
    // if (selectPicker.length) {
    //   selectPicker.each(function () {
    //     var $this = $(this);
    //     $this.selectpicker().on('change', function () {
    //       FormValidation2.revalidateField('formValidationLanguage');
    //     });
    //   });
    // }

    // select2
    // if (select2.length) {
    //   select2.each(function () {
    //     var $this = $(this);
    //     $this.wrap('<div class="position-relative"></div>');
    //     $this
    //       .select2({
    //         placeholder: 'Select an country',
    //         dropdownParent: $this.parent()
    //       })
    //       .on('change.select2', function () {
    //         // Revalidate the color field when an option is chosen
    //         FormValidation2.revalidateField('formValidationCountry');
    //       });
    //   });
    // }

    // Social links
    const FormValidation3 = FormValidation.formValidation(wizardValidationFormStep3, {
      fields: {
        no_hp: {
          validators: {
            notEmpty: {
              message: 'No. HP Harap diisi'
            }
          }
        }
      },
      plugins: {
        trigger: new FormValidation.plugins.Trigger(),
        bootstrap5: new FormValidation.plugins.Bootstrap5({
          // Use this for enabling/changing valid/invalid class
          // eleInvalidClass: '',
          eleValidClass: '',
          rowSelector: '.col-md-12'
        }),
        autoFocus: new FormValidation.plugins.AutoFocus(),
        submitButton: new FormValidation.plugins.SubmitButton()
      }
    }).on('core.form.valid', function () {
      // You can submit the form
      // wizardValidationForm.submit()
      // or send the form data to server via an Ajax request
      // To make the demo simple, I just placed an alert
      const myForm = $('#wizard-validation-form').serialize();
      $.ajax({
        data: myForm,
        url: ''.concat(baseUrl).concat('psb/validation'),
        type: 'POST',
        success: function (data) {
          data = JSON.parse(data);
          //alert(data.msg);
          if (parseInt(data[0].code) == 0) {
            //alert("masuk sini");
            $('#alert-show').html('');
            const urlSend = ''.concat(baseUrl).concat('psb');
            $.ajax({
              url: urlSend,
              data: myForm,
              method: 'POST',
              success: function (data) {
                Swal.fire({
                  icon: 'success',
                  title: 'Tersimpan',
                  text: 'Data berhasil disimpan',
                  customClass: {
                    confirmButton: 'btn btn-success'
                  }
                });
                data = JSON.parse(data);
                $('#wizard-validation').html(`
                                        <div class="row">
                                            <div class="col-md-12" style="padding:30px">
                                                <p>Selamat anda sudah terdaftar pada web Penerimaan Peserta Didik Baru PPATQ Radlatul Falah Pati</p>
                                                <p>Silahkan catat username dan password di bawah ini untuk dapat mengubah dan melengkapi data</p>
                                                <p><b>username : ${data[0].username} </b></p>
                                                <p><b>password : ${data[0].password} </b></p>
                                                <p>Selanjutnya anda dapat melakukan pengkinian data dan mengupload berkas pendukung calon santri baru di menu PSB setelah login melalui sistem
                                                https://psb.ppatq-rf.id melalui menu update data / upload berkas pendukung</p>
                                            </div>
                                        </div>
                                        `);
              }
            });
          } else {
            //alert(data.msg);
            Swal.fire({
              icon: 'error',
              title: 'Data Submit Error',
              text: 'Data tidak dapat disimpan harap periksa pesan error',
              customClass: {
                confirmButton: 'btn btn-success'
              }
            });
            $('#alert-show').html('');
            data.forEach(function (item) {
              $('#alert-show').prepend("<div class='alert alert-danger'>" + item.msg + '</div>');
            });
          }
        }
      });
    });

    wizardValidationNext.forEach(item => {
      item.addEventListener('click', event => {
        // When click the Next button, we will validate the current step
        switch (validationStepper._currentIndex) {
          case 0:
            FormValidation1.validate();
            break;

          case 1:
            FormValidation2.validate();
            break;

          case 2:
            FormValidation3.validate();
            break;

          default:
            break;
        }
      });
    });

    wizardValidationPrev.forEach(item => {
      item.addEventListener('click', event => {
        switch (validationStepper._currentIndex) {
          case 2:
            validationStepper.previous();
            break;

          case 1:
            validationStepper.previous();
            break;

          case 0:

          default:
            break;
        }
      });
    });
  }
})();
