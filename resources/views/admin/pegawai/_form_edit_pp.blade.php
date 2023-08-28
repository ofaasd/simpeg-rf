<Form action="javascript:void(0)" enctype="multipart/form-data" class="add-new-{{strtolower($title)}} pt-0" id="addNew{{$title}}Form">
  @csrf
  <input type="hidden" id="id" name="id" id="{{strtolower($title)}}_id" value='{{$var['EmployeeNew']->id}}'>
  <div class="flex-shrink-0 mt-n2 mx-sm-0 mx-auto" style="margin-bottom:20px">
    <img src="{{ (empty($var['EmployeeNew']->photo))?asset('assets/img/avatars/1.png'):asset('assets/img/upload/photo/' . $var['EmployeeNew']->photo)}}" alt="user image" class="d-block h-auto ms-0 ms-sm-4 rounded user-profile-img">
  </div>
  <div class="form-floating form-floating-outline mb-4 col-md-4">
    <input type="file" class="form-control" name="photos" />
    <label for="add-{{strtolower($title)}}-photo">Ubah Photo Profile</label>
  </div>
  <input type="submit" value="Ganti" class="btn btn-primary me-sm-3 me-1 data-submit">
</Form>
