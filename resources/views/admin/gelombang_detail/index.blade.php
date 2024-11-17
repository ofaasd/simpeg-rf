@extends('layouts/layoutMaster')

@section('title', 'Gelombang PSB' . ' Management - Crud App')

@section('vendor-style')
<link rel="stylesheet" href="{{asset('assets/vendor/libs/quill/typography.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/quill/katex.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/quill/editor.css')}}" />
@endsection

@section('vendor-script')
<script src="{{asset('assets/vendor/libs/quill/katex.js')}}"></script>
<script src="{{asset('assets/vendor/libs/quill/quill.js')}}"></script>
@endsection
@section('page-style')
  <style>
    .ql-container {
      height: calc(100% - 120px);
    }
  </style>
@endsection

@section('content')
<!-- {{strtolower($title)}} List Table -->
<div class="card">
  <div class="card-header">
    <h5 class="card-title mb-0">Detail Informasi Gelombang</h5>
  </div>
  <div class="card-body">
    <form id="form-gelombang-detail">
      <input type="hidden" name="id" id="id" value="{{$id}}">
        <div class="mb-4 mt-4">
          <label for="add-hari"><h5>Hari / Tanggal</h5></label>
          <div class="ql-container">
            <div id="full-editor">
              {!!$detail->hari!!}
            </div>
          </div>
        </div>
        <div class="mb-4 mt-4">
          <label for="add-hari"><h5>Jam</h5></label>
          <div class="ql-container">
            <div id="full-editor2">
              {!!$detail->jam!!}
            </div>
          </div>
        </div>
        <div class="mb-4 mt-4">
          <label for="add-hari"><h5>Syarat</h5></label>
          <div class="ql-container">
            <div id="full-editor3">
              {!!$detail->syarat!!}
            </div>
          </div>
        </div>
        <div class="mb-4 mt-4">
          <label for="add-hari"><h5>Prosedur Online</h5></label>
          <div class="ql-container">
            <div id="full-editor4">
              {!!$detail->prosedur_online!!}
            </div>
          </div>
        </div>
        <div class="mb-4 mt-4">
          <label for="add-hari"><h5>Prosedur Offline</h5></label>
          <div class="ql-container">
            <div id="full-editor5">
              {!!$detail->prosedur_offline!!}
            </div>
          </div>
        </div>
        <div class="mb-4 mt-4">
          <button id="simpanBtn" class="btn btn-primary">Save</button>
        </div>
    </form>
  </div>
</div>
@endsection

  <script>
    const fullToolbar = [
    [
      {
        font: []
      },
      {
        size: []
      }
    ],
    ['bold', 'italic', 'underline', 'strike'],
    [
      {
        color: []
      },
      {
        background: []
      }
    ],
    [
      {
        script: 'super'
      },
      {
        script: 'sub'
      }
    ],
    [
      {
        header: '1'
      },
      {
        header: '2'
      },
      'blockquote',
      'code-block'
    ],
    [
      {
        list: 'ordered'
      },
      {
        list: 'bullet'
      },
      {
        indent: '-1'
      },
      {
        indent: '+1'
      }
    ],
    [{ direction: 'rtl' }],
    ['link', 'image', 'video', 'formula'],
    ['clean']
  ];
    document.addEventListener("DOMContentLoaded", function(event) {
      //alert('masuk sini');
      const fullEditor = new Quill('#full-editor', {
        bounds: '#full-editor',
        placeholder: 'Type Something...',
        modules: {
          formula: true,
          toolbar: fullToolbar
        },
        theme: 'snow'
      });
      const fullEditor2 = new Quill('#full-editor2', {
        bounds: '#full-editor2',
        placeholder: 'Type Something...',
        modules: {
          formula: true,
          toolbar: fullToolbar
        },
        theme: 'snow'
      });
      const fullEditor3 = new Quill('#full-editor3', {
        bounds: '#full-editor3',
        placeholder: 'Type Something...',
        modules: {
          formula: true,
          toolbar: fullToolbar
        },
        theme: 'snow'
      });
      const fullEditor4 = new Quill('#full-editor4', {
        bounds: '#full-editor4',
        placeholder: 'Type Something...',
        modules: {
          formula: true,
          toolbar: fullToolbar
        },
        theme: 'snow'
      });
      const fullEditor5 = new Quill('#full-editor5', {
        bounds: '#full-editor5',
        placeholder: 'Type Something...',
        modules: {
          formula: true,
          toolbar: fullToolbar
        },
        theme: 'snow'
      });
      $("#simpanBtn").click(function(){
        $(this).prop('disabled',true);
        $(this).text('Mohon Tunggu');
        const baseUrl = '{!! url('') !!}';
        const title = '{{$title}}';
        $.ajax({
          url:baseUrl.concat('/gelombang_detail'),
          method:"POST",
          data:{
            'id' : $("#id").val(),
            'hari' : fullEditor.root.innerHTML,
            'jam' : fullEditor2.root.innerHTML,
            'syarat' : fullEditor3.root.innerHTML,
            'prosedur_online' : fullEditor4.root.innerHTML,
            'prosedur_offline' : fullEditor5.root.innerHTML,
          },
          success : function(status){
            Swal.fire({
              icon: 'success',
              title: 'Successfully '.concat(status, '!'),
              text: ''.concat(title, ' ').concat(status, ' Successfully.'),
              customClass: {
                confirmButton: 'btn btn-success'
              }
            });
            $("#simpanBtn").prop('disabled',false);
            $("#simpanBtn").text('Save');
          },
          error: function error(err) {
            Swal.fire({
              title: 'Error',
              text: title + ' Not Saved !',
              icon: 'error',
              customClass: {
                confirmButton: 'btn btn-success'
              }
            });
            $("#simpanBtn").prop('disabled',false);
            $("#simpanBtn").text('Save');
          },
        })
      })
    });
  </script>
