@extends('layouts.newapp')

@section('headertitle')
 <h1><i class="fa fa-dashboard"></i> User page</h1>
  <p>update your information here</p>


@endsection



@section('breadcrumb')

<li class="breadcrumb-item"><a href="#">User page</a></li>

@endsection

@section('content')
      <div class="row user">
        
        <div class="col-md-3">
          <div class="tile p-0">
            <ul class="nav flex-column nav-tabs user-tabs">
              <li class="nav-item"><a class="nav-link active show" href="#user-settings" data-toggle="tab">Settings</a></li>
              <li class="nav-item"><a class="nav-link show" href="#user-followings" data-toggle="tab">Follow( {{ $followsCount[0]->follows }} )</a></li>
              <li class="nav-item"><a class="nav-link show" href="#user-followeds" data-toggle="tab">Follows( {{$followingsCount[0]->followings }} )</a></li>
            </ul>
          </div>
        </div>
        <div class="col-md-9">
          <div class="tab-content">
            
            <div class="tab-pane fade active show" id="user-settings">
              @if ($errors->any())
                <div class="alert alert-danger">
                  <ul>
                    @foreach ($errors->all() as $error)
                      <li>{{ $error }}</li>
                    @endforeach
                  </ul>
              </div>
            @endif

              <div class="tile user-settings">
                <h4 class="line-head">Settings</h4>
                <form method="POST" action="/user">
                   @csrf 
                  <div class="row mb-4">
                    <div class="col-md-4">
                      <label>ID</label>
                      <input readonly class="form-control" type="text" value="{{ Auth::user()->global_id }}">
                    </div>
                    <div class="col-md-4">
                      <label>Name</label>
                      <input class="form-control" type="text" value="{{ Auth::user()->name }}" name="name">
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-8 mb-4">
                      <label>Email (Local)</label>
                      <input class="form-control" type="text" value="{{ Auth::user()->email }}" name="email">
                    </div>
                    <div class="clearfix"></div>
                  </div>

                  <div class="row mb-10">
                    <div class="col-md-12">
                     <button class="btn btn-primary" type="submit"><i class="fa fa-fw fa-lg fa-check-circle"></i> Save</button>
                    </div>
                  </div>
                </form>
              </div>
            </div>

              <div class="tab-pane fade show" id="user-followings">
              <div class="tile user-settings">
                <h4 class="line-head">You Follow</h4>
                
           
            <table class="table">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Name</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>

                  @foreach($follows as $follow)
                <tr>
                   <td><a style="color:black" href="/users/{{ $follow->secret }}">{{ $follow->global_id }}</a></td>
                  <td><a style="color:black" href="/users/{{ $follow->secret }}">{{ $follow->name }}</a></td>
                

                  <td>
                    <form method="POST" action="/follows/{{ $follow->secret }}">
                      @csrf  
                      <button type="submit" class="btn btn-danger">Unfollow</button>
                    </form>
                  </td>
                </tr>
                @endforeach
               
              </tbody>
            </table>
          

              </div>
            </div>

            <div class="tab-pane fade show" id="user-followeds">
              <div class="tile user-settings">
                <h4 class="line-head">Followings you</h4>
                
                 <table class="table">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Name</th>
                </tr>
              </thead>
              <tbody>

                  @foreach($followings as $follow)
                <tr>
                  <td><a style="color:black" href="/users/{{ $follow->secret }}">{{ $follow->global_id }}</a></td>
                  <td><a style="color:black" href="/users/{{ $follow->secret }}">{{ $follow->name }}</a></td>

                </tr>
                @endforeach
               
              </tbody>
            </table>


              </div>
            </div>



          </div>
        </div>
      </div>
  @endsection
  