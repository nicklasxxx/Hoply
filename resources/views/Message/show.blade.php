@extends('layouts.newapp')


@section('headertitle')

  <div>
  <h1><i class="fa fa-th-list"></i> Chat</h1>
  <p>Chat with your friends</p>
</div>

@endsection



@section('breadcrumb')

<li class="breadcrumb-item active"><a href="#">Chat</a></li>

@endsection



@section('content')

      <div class="row">
        <div class="col-md-12">
          <div class="tile">
            <h3 class="tile-title-w-btn">
               <a href="/users/{{$user->id}}" style="color:black; float:left;">Chat with: {{$user->name}}</a> 
              <form method="POST" action="/message/update">
                @csrf
            <button type="submit" class="btn btn-primary">Update</button>
          </form>
            </h3>
            <div class="messanger">
              <div class="messages" id="messender">

                @foreach($messages as $message)
                  
                  @php $me = ($message->sender == Auth::user()->global_id? "me":""); @endphp

                    <div class="message {{ $me }}"><img src="/upload/profile.jpg" height="40px" width="40px">
                      @if($message->type == 'bin')
                      <img src="{{ $message->body }}" height="240px" width="240px"> 
                      @else
                      <p class="info">{{ $message->body }}</p>
                      @endif
                    </div>
                    @endforeach
              </div>
              <div class="sender">
                <input type="text" placeholder="Send Message" id="message">
                <button class="btn btn-primary" id="send" type="button"><i class="fa fa-lg fa-fw fa-paper-plane"></i>Send</button>
                <button class="btn btn-primary" id="location" onclick="postLocation()" type="button">Share position</i></button>
                <button class="btn btn-primary" id="send" data-toggle="modal" data-target="#exampleModal" type="button">Upload Image</i></button>
              </div>
            </div>
          </div>
        </div>
      </div>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
     <form action="/message/post/image" method="POST" enctype="multipart/form-data">
      @csrf
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Upload Image</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="form-group">
      <label for="exampleFormControlFile1">Example file input</label>
      <input type="file" class="form-control-file" name="image" id="exampleFormControlFile1">
      <input type="hidden" name="user" value="{{$user->id}}">

    </div>


      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Upload</button>
      </div>
    </form>
    </div>
  </div>
</div>


@endsection


@section('javascript')

 <!-- Data table plugin-->
    <script type="text/javascript" src="{{ asset('js/plugins/jquery.dataTables.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/plugins/dataTables.bootstrap.min.js') }}"></script>
    <script type="text/javascript">$('#sampleTable').DataTable();</script>


  <script>

      $(document).ready(function(){

        $("#messender").scrollTop($("#messender")[0].scrollHeight);


        $("#send").on("click", function(){

          var message = $("#message").val();

          if(message != ""){
         $("#message").val("");
         $("#message").attr("placeholder", "Send Message");


          $("#messender").append('<div class="message me"><img src="/upload/profile.jpg" height="40px" width="40px"> <p class="info">'+message+'</p></div>');

            $("#messender").scrollTop($("#messender")[0].scrollHeight);
             postMessage(message, 'txt');
            }

            });
    





          });

function postMessage(message, type)
{

  $.ajaxSetup({
    headers: {
     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
     }
        });
     
$.ajax({
  type: "POST",
  url: "/message/post",
  data: {

    receiver: {{$user->id}},
    body: message,
    type: type
  },

  dataType: 'json'
}).done(function(d) {
  if(d.status === "success"){
    
    alert('succes');

    }else{
     alert('Error happen');
    }

  });
}


function postLocation()
{

   if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(showPosition);
  } else { 
    x.innerHTML = "Geolocation is not supported by this browser.";
  }

  function showPosition(position) {
        var message = position.coords.latitude + " " + position.coords.longitude; 

         $("#messender").append('<div class="message me"><img src="/upload/profile.jpg" height="40px" width="40px"> <p class="info">'+message+'</p></div>');
            postMessage(message, 'gps');
            
            $("#messender").scrollTop($("#messender")[0].scrollHeight);
}

}

    </script>


    @endsection




