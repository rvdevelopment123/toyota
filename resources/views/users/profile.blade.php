@extends('app')

@section('contentheader')
	{{$user->name}}'s {{ trans('core.profile') }}
@stop

@section('breadcrumb')
	{{ trans('core.profile') }}
@stop

@section('main-content')
<div class="panel-body">
	<div class="row">
    <div class="col-md-3" >
      <!-- Profile Image -->
      <div class="box box-primary" style="padding-bottom: 50px;">
        <center>
          <div class="box-body box-profile">
            @if(empty($user->image))
              <img src="{{asset('img/default.png')}}" class="img-thumbnail img-responsive thumb-large" alt="{{ $user->name }}" />
            @else
              <img src="{!! asset('uploads/profiles/'. $user->image)!!}" class="img-responsive img-thumbnail thumb-large" alt="User Image"/>
            @endif

            <h3 class="profile-username text-center">
            	{{title_case($user->name)}}
            </h3>

            <p class="text-muted text-center">
              @foreach($user->roles as $role)
            	 {{ $role->name }}
              @endforeach
            </p>
            <div class="btn-group">
              <!-- tabs action starts-->
              <a href="#details" data-toggle="tab" class="btn btn-xs btn-info">
                {{ trans('core.details') }}
              </a>
              <a href="#editProfile" data-toggle="tab" class="btn btn-xs btn-success">
                {{ trans('core.edit_profile') }}
              </a>
              <a href="#change_password" data-toggle="tab" class="btn btn-xs btn-warning">
                {{ trans('core.change_password') }}
              </a>
              <!-- tab action ends -->
            </div>

          </div>  <!-- /.box-body -->
        </center>
      </div> <!-- /.box -->    
    </div> <!-- /.col -->
    
    <div class="col-md-9">
      <div class="nav-tabs-custom">
        <div class="tab-content">
          <!-- Details tab starts -->
          <div class="active tab-pane" id="details" style="padding-bottom: 50px;">
          	<h4>{{ trans('core.details') }}</h4>
          	<hr />
	          <div class="row">
	              <div class="col-md-12">
	                <label>{{ trans('core.email') }}</label>
	                <div class="form-control" >{{$user->email}}</div>
	            </div>

              @if($user->phone)
  	            <div class="col-md-12">
  	                <label>{{ trans('core.phone') }}</label>
  	                <div class="form-control">{{$user->phone}}</div>
  	            </div>
              @endif
	          </div>

            @if($user->address)
  	          <div class="form-group">
  	              <label>{{ trans('core.address') }}</label>
  	              <div class="form-control" style="background-color: #ddd;">{{$user->address}}</div>
  	          </div>
            @endif
          </div> 
          <!-- /.Details tab ends -->

          <!-- Edit Profile tab starts -->
          <div class="tab-pane" id="editProfile">
            <h4>{{ trans('core.edit_profile') }}</h4><hr />
          	<form class="form-horizontal" method="post" action="{{route('user.profile.post')}}">
          		{{csrf_field()}}
          	  <input type="hidden" name="user_id" value="{{$user->id}}">
              <div class="form-group">
                <label for="inputName" class="col-sm-2 control-label">First Name</label>
                <div class="col-sm-5">
                  <input type="text" class="form-control" id="inputName" value="{{$user->first_name}}" name="first_name" @if($user->hasRole('Super User')) disabled="true" title="You Can't Edit This Section" @endif>
                </div>
                <div class="col-sm-5">
                  <input type="text" class="form-control" id="inputName" value="{{$user->last_name}}" name="last_name" @if($user->hasRole('Super User')) disabled="true" title="You Can't Edit This Section" @endif>
                </div>

              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label">{{ trans('core.email') }}</label>

                <div class="col-sm-10">
                  <input type="email" class="form-control" value="{{$user->email}}" name="email" @if($user->hasRole('Super User')) disabled="true" title="You Can't Edit This Section" @endif>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label">{{ trans('core.phone') }}</label>

                <div class="col-sm-10">
                  <input type="text" class="form-control"  value="{{$user->phone}}" name="phone">
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label">{{ trans('core.address') }}</label>

                <div class="col-sm-10">
                  <textarea class="form-control" name="address">{{$user->address}}</textarea>
                </div>
              </div>
              
              <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                  <button type="submit" class="btn btn-success">
                    {{ trans('core.update') }}
                  </button>
                </div>
              </div>
            </form>
          </div>
          <!-- Edit Profile Ends -->

          <!-- Password Change Tab Starts -->
          <div class="tab-pane" id="change_password">
            <h4>{{ trans('core.change_password') }}</h4><hr/>
          	<form class="form-horizontal" method="post" action="{{route('change.password')}}">
          	  {{csrf_field()}}
          	  <input type="hidden" name="user_id" value="{{$user->id}}">
              <div class="form-group">
                <label class="col-sm-2 control-label">
                  {{ trans('core.old_password') }}
                </label>
                <div class="col-sm-10">
                  <input id="password" type="password" class="form-control" placeholder="Type your old password"  name="old_password">

                  <!-- Alert Message Shows on Password Match State -->
                  <p id="correct" style="color: green;">
                    <i class="fa fa-check"></i> Valid
                  </p>
                  <p id="incorrect" style="color: red;">
                    <i class="fa fa-times"></i> Oops! Old password does not match!
                  </p>
                  <!-- Ends -->
                  
                </div>
              </div>

              <div id="pasword_change_form">
                <div class="form-group">
                  <label for="inputName" class="col-sm-2 control-label">
                    {{ trans('core.new_password') }}
                  </label>
                  <div class="col-sm-10">
                    <input id="new_password" type="password" class="form-control" placeholder="Type Your New Password"  name="password" required>
                  </div>
                </div>

                <div class="form-group">
                  <label for="inputName" class="col-sm-2 control-label">
                    {{ trans('core.re_type_password') }}
                  </label>
                  <div class="col-sm-10">
                    <input id="new_pass_conf" type="password" class="form-control" placeholder="Re Type Password"  name="confirm_password" required>
                    <p id="match" style="color: green;">
                      <i class="fa fa-check"></i> Password Match
                    </p>
                    <p id="mis_match" style="color: red;">
                      <i class="fa fa-times"></i> Your Passwords don't Match
                    </p>
                  </div>
                </div>
              </div>

              <div id="submit">
                <div class="form-group">
                  <div class="col-sm-offset-2 col-sm-10">
                    <button id="submit" type="submit" class="btn btn-success" id="submit">
                      {{ trans('core.submit') }}
                    </button>
                  </div>
                </div>
              </div>
            </form>
          </div> <!-- Password Change Tab Ends -->
        </div> <!-- Tab-content ends-->  
      </div> <!-- Nav-tabs-custom -->
    </div> <!-- col-9 Ends-->   
  </div> <!-- row ends -->
</div>
@stop

@section('js')
	@parent
  <script src="/assets/js-core/axios.min.js"></script>
	<script>

    var current_password = "{{$user->password}}";
    $('#pasword_change_form').hide();
    $('#correct').hide();
    $('#match').hide();
    $('#incorrect').hide();
    $('#mis_match').hide();
    $('#submit').hide();
    var password;
    var new_password;
    var new_pass_conf;
    $(document).ready(function(){
        $('#password').on('change', function(){
            password = $(this).val();
            var route = {!! json_encode(route('user.old-password')) !!}
            axios.post(route, { password: password})
              .then(function (response) {
                $('#pasword_change_form').show();
                $('#correct').show();
                $('#incorrect').hide();
              })
              .catch(function (response) {
                $('#correct').hide();
                $('#incorrect').show();
                $('#pasword_change_form').hide();
              })    
        });

        $('#new_password').keyup(function(){
            new_password = $(this).val();         
        });

        $('#new_pass_conf').keyup(function(){
            new_pass_conf = $(this).val();
            if(new_pass_conf == new_password){
              $('#match').show();
              $('#mis_match').hide();
              $('#submit').show();
            }else{
              $('#mis_match').show();
              $('#match').hide();
              $('#submit').hide();
            }  
        });

    });	
	</script>
@stop