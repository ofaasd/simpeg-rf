@extends('layouts/layoutMaster')

@section('title', $title . ' Management - Crud App')

@section('vendor-style')
<link rel="stylesheet" href="{{asset('assets/vendor/libs/bs-stepper/bs-stepper.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/bootstrap-select/bootstrap-select.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/select2/select2.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/formvalidation/dist/css/formValidation.min.css')}}" />
@endsection

@section('vendor-script')
<script src="{{asset('assets/vendor/libs/bs-stepper/bs-stepper.js')}}"></script>
<script src="{{asset('assets/vendor/libs/bootstrap-select/bootstrap-select.js')}}"></script>
<script src="{{asset('assets/vendor/libs/select2/select2.js')}}"></script>
<script src="{{asset('assets/vendor/libs/formvalidation/dist/js/FormValidation.min.js')}}"></script>
<script src="{{asset('assets/vendor/libs/formvalidation/dist/js/plugins/Bootstrap5.min.js')}}"></script>
<script src="{{asset('assets/vendor/libs/formvalidation/dist/js/plugins/AutoFocus.min.js')}}"></script>
@endsection

@section('page-script')
<script src="{{asset('assets/js/form-wizard-numbered.js')}}"></script>
<script src="{{asset('assets/js/form-wizard-validation.js')}}"></script>
@endsection

@section('content')
<!-- {{strtolower($title)}} List Table -->

    <div class="col-12 mb-4">
      <small class="text-light fw-semibold">Validation</small>
      <div id="wizard-validation" class="bs-stepper mt-2">
        <div class="bs-stepper-header">
          <div class="step" data-target="#account-details-validation">
            <button type="button" class="step-trigger flex-lg-wrap gap-lg-2 px-lg-0">
              <span class="bs-stepper-circle"><i class="mdi mdi-check"></i></span>
              <span class="bs-stepper-label ms-lg-0">
                <span class="d-flex flex-column gap-1 text-lg-center">
                  <span class="bs-stepper-title">Data Pribadi</span>
                  <!-- <span class="bs-stepper-subtitle">Detail Pribadi Santri</span> -->
                </span>
              </span>
            </button>
          </div>
          <div class="line mt-lg-n4 mb-lg-3"></div>
          <div class="step" data-target="#personal-info-validation">
            <button type="button" class="step-trigger flex-lg-wrap gap-lg-2 px-lg-0">
              <span class="bs-stepper-circle"><i class="mdi mdi-check"></i></span>
              <span class="bs-stepper-label ms-lg-0">
                <span class="d-flex flex-column gap-1 text-lg-center">
                  <span class="bs-stepper-title">Data Wali Santri</span>
                  <!-- <span class="bs-stepper-subtitle">Input data Walsan</span> -->
                </span>
              </span>
            </button>
          </div>
          <div class="line mt-lg-n4 mb-lg-3"></div>
          <div class="step" data-target="#social-links-validation">
            <button type="button" class="step-trigger flex-lg-wrap gap-lg-2 px-lg-0">
              <span class="bs-stepper-circle"><i class="mdi mdi-check"></i></span>
              <span class="bs-stepper-label ms-lg-0">
                <span class="d-flex flex-column gap-1 text-lg-center">
                  <span class="bs-stepper-title">Data Sekolah</span>
                  <!-- <span class="bs-stepper-subtitle">Add social links</span> -->
                </span>
              </span>
            </button>
          </div>
        </div>
        <div class="bs-stepper-content">
          <div id="alert-show"></div>
          <form id="wizard-validation-form" onSubmit="return false">
            <!-- Account Details -->
            <div id="account-details-validation" class="content">
              @include('admin/psb/_form_data_diri')
            </div>
            <!-- Personal Info -->
            <div id="personal-info-validation" class="content">
              @include('admin/psb/_form_data_wali_santri')
            </div>
            <!-- Social Links -->
            <div id="social-links-validation" class="content">
              @include('admin/psb/_form_data_sekolah')
            </div>
          </form>
        </div>
      </div>
    </div>

@endsection
