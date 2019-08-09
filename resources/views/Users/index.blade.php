@extends('layouts.newapp')


@section('headertitle')

  <div>
  <h1><i class="fa fa-th-list"></i> Users</h1>
  <p>List All Users of Hoply</p>
</div>

@endsection



@section('breadcrumb')

<li class="breadcrumb-item active"><a href="#">Users</a></li>

@endsection



@section('content')

      <div class="row">
        <div class="col-md-12">
          <div class="tile">
            <div class="tile-body">
              <form method="POST" action="/users/update">
                @csrf
            <button type="submit" class="btn btn-light">Update users</button>
          </form>
            <br><br>
         
                <table class="table table-hover table-bordered dataTable no-footer" id="sampleTable" role="grid" aria-describedby="sampleTable_info">
                
                <thead>
                  <tr role="row">
                    <th>ID</th>
                    <th>Name</th>
                    <th>Following</th>
                    <th>Message</th>
                    <th>Created at</th>
                  </tr>
                </thead>
              <tbody>
                  
                  @foreach($users as $user)
                  <tr>
                    <td style="max-width: 250px"><a style="color:black" href="/users/{{ $user->secret }}">{{ $user->id }}</a></td>
                    <td style="max-width: 250px"><a style="color:black" href="/users/{{ $user->secret }}">{{ $user->name }}</a></td>
                    <td>
                    <form method="POST" action="/follows/{{ $user->secret }}">
                      @csrf  
                      
                      @if($user->follows == 'follow')
                      <button type="submit" class="btn btn-secondary">{{ $user->follows }}</button>
                      @else
                     <button type="submit" class="btn btn-success">Following</button>
                      @endif 
                      <br>
                      <small>{{ $user->following }}</small>
                      </form>
                    </td>
                    <td> 
                      <a href="/message/{{ $user->secret }}" class="btn btn-secondary">New message</a>
                    </td>
                    <td>{{ $user->created_at }} </td>
                  </tr>

                  @endforeach

                </tbody>
              </table>

            </div>
          </div>


@endsection


@section('javascript')

 <!-- Data table plugin-->
    <script type="text/javascript" src="js/plugins/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="js/plugins/dataTables.bootstrap.min.js"></script>
    <script type="text/javascript">$('#sampleTable').DataTable();</script>

    @endsection




