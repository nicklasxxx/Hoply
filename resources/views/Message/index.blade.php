@extends('layouts.newapp')


@section('headertitle')

  <div>
  <h1><i class="fa fa-th-list"></i> Messages</h1>
  <p>List All your messages Hoply</p>
</div>

@endsection



@section('breadcrumb')

<li class="breadcrumb-item active"><a href="#">Messages</a></li>

@endsection



@section('content')

      <div class="row">
        <div class="col-md-12">
          <div class="tile">
            <div class="tile-body">
             <form method="POST" action="/message/update">
                @csrf
            <button type="submit" class="btn btn-light">Update messages</button>
          </form>
            <br><br>

                <table class="table table-hover table-bordered dataTable no-footer" id="sampleTable" role="grid" aria-describedby="sampleTable_info">
                
                <thead>

                  <tr>
                    <th>ID</th>
                    <th>Lastest message</th>
                    <th>Date</th>
                  </tr>

                </thead>
                <tbody>
                  
                  @foreach($messages as $message)
                <tr>
                    <td><a href="/message/{{$message->id}}" style="color:black">
                      @if($message->ss == Auth::user()->global_id)
                      {{$message->rr}}
                      @else
                      {{ $message->ss }}  
                      @endif </a>
                    </td>
                    <td><a href="/message/{{$message->id}}" style="color:black">(@if($message->ss == Auth::user()->global_id) You @else {{ $message->ss }} @endif) 
                      @if($message->type != 'bin') {{ $message->type }} {{ $message->body }} @else File @endif</a></td>
                    <td><a href="/message/{{$message->id}}" style="color:black">{{ $message->date }}</a></td>
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




