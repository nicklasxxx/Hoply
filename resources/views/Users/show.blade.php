@extends('layouts.newapp')

@section('headertitle')
 <h1><i class="fa fa-dashboard"></i> see User</h1>
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
              <li class="nav-item"><a class="nav-link active show" href="#user-settings" data-toggle="tab">Info</a></li>
              <li class="nav-item"><a class="nav-link show" href="#user-followings" data-toggle="tab">Follow( {{ $followsCount[0]->follows }} )</a></li>
              <li class="nav-item"><a class="nav-link show" href="#user-followeds" data-toggle="tab">Followings( {{$followingsCount[0]->followings }} )</a></li>
            </ul>
          </div>
        </div>
        <div class="col-md-9">
          <div class="tab-content">
            
            <div class="tab-pane fade active show" id="user-settings">
              <div class="tile user-settings">
                <h4 class="line-head">Info</h4>
                

                     <div class="row">
                    <div class="col-md-12 mb-4">
                      <label>ID</label>
                      <input readonly class="form-control" type="text" value="{{ $user->global_id }}">
                    </div>
                  </div>

                    <div class="row">
                    <div class="col-md-12 mb-4">
                      <label>Name</label>
                      <input readonly class="form-control" type="text" value="{{ $user->name }}" name="name">
                    </div>
                    </div>
                  
                  <div class="row">
                    <div class="col-md-12 mb-4">
                      <label>Email (Local)</label>
                      <input readonly class="form-control" type="text" value="{{ $user->email }}" name="email">
                    </div>
                    <div class="clearfix"></div>
                  </div>

                 <div class="row mb-10">
                    <div class="col-md-12" style="inline">

                     <form method="POST" action="/follows/{{ $user->id }}">
                      @csrf  
                      
                      @if($currentUserFollowing == null)
                      <button type="submit" class="btn btn-secondary">Following</button>
                      @else
                     <button type="submit" class="btn btn-success">Follows</button>
                      @endif 
                      </form>
                      <br>
                      
                      <form method="POST" action="/users/update/{{$user->id}}">
                        @csrf 

                      <a href="/message/{{ $user->id }}" class="btn btn-secondary">New message</a>  
                      <button type="submit" class="btn btn-secondary">Update user info</button>
                    </form>

                    </div>
                  </div>
                
              </div>
            </div>

              <div class="tab-pane fade show" id="user-followings">
              <div class="tile user-settings">
                <h4 class="line-head">Follow</h4>
                
           
            <table class="table">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Name</th>
                </tr>
              </thead>
              <tbody>

                  @foreach($follows as $follow)
                <tr>
                  <td><a style="color:black" href="/users/{{ $follow->secret }}">{{ $follow->global_id }}</a></td>
                  <td><a style="color:black" href="/users/{{ $follow->secret }}">{{ $follow->name }}</a></td>
                
                </tr>
                @endforeach
               
              </tbody>
            </table>
          

              </div>
            </div>

            <div class="tab-pane fade show" id="user-followeds">
              <div class="tile user-settings">
                <h4 class="line-head">Followings</h4>
                
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
  