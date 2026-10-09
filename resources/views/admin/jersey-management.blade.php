@extends('layouts.app')
@section('title','Jersey Management')
    @section('content')
    <main>
				<div class="influ-strip-2">
					<div class="influ-search">
						<form>
							{{-- <label for="">
								<input type="text" name="" placeholder="Search by country">
								<button><img src="images/search.png" alt=""></button>
							</label> --}}
						</form>
					</div>
					<div class="influ-btns">

						<form id="statistic_filter">
							<div class="influ-search">

							<!-- 1 -->
                            <label for="">
								<input type="text" name="search" placeholder="Search by country" id="search" value="{{$request->search??''}}">
								<button><img src="{{asset('assets/images/search-jb.png')}}" alt=""></button>
							</label>
							</div> 

							<!-- 2 -->

							<a href="javascript:void(0)"class="influ-btn" data-toggle="modal" data-target="#addModal">ADD </a>

					</div>
                </form>
				</div>
				<div class="influ-table" id="jersey">
					<div id="table-responsive-1" class="table-responsive">

						<table>
							<tr>
								<th>S. No.</th>
								<th>Country</th>
                                <th>year</th>
								<th>jersey image</th>
                                <th>type</th>
                                <th>Action</th>
								
							</tr>
                            @php
                        $i = $Jersey->perPage() * ($Jersey->currentPage() - 1);
                    @endphp
							   @foreach($Jersey as $index=>$row)
                                <tr>
                                    <td>{{++$i}}.</td>
                                    <td>{{ $row->country->name }}</td>
                                    <td>{{$row->year}}</td>
                                   
                                    <td><img src="{{asset($row->jersey_image)}}" style="width: 134px;"></td>
                                     <!-- <td>{{($row->type==0 ? 'Player' :($row->type==1 ? 'GoalKeeper' :($row->type==2 ? 'Gold' :($row->type==3 ? 'Purple' : ($row->type==4 ? 'Red' : 'unknown')))))}}</td> -->
                                      <td>
                                         {{
                                             match($row->type) {
                                                 0 => 'Player',
                                                 1 => 'GoalKeeper',
                                                 2 => 'Gold',
                                                 3 => 'Purple',
                                                 4 => 'Red',
                                                 5 => 'Gold (GK)',
                                                 6 => 'Purple (GK)',
                                                 7 => 'Red (GK)',
                                                 default => 'Unknown'
                                             }
                                         }}
                                       </td>
                                    <td>
                                         <a type="button"class="show-modal" onclick="edit_jearcy('{{$row->id}}')">
                                <img src="{{asset('assets/images/edit.png')}}"alt=""></a>
                                        
                                    <a type="button"class="show-modal" onclick="conform_delete('{{$row->id}}')">
                                <img src="{{asset('assets/images/remove.png')}}"alt=""></a>
                                

                               
                            
                            </td>
                                    
                                </tr>
                                @endforeach
                         




						</table>
			
                        {!! $Jersey->onEachSide(1)->links('layouts.paginate') !!}
				</div>
			</main>

                <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
                <div class="modal-dialog" role="document">
                <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Add Jersey</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <form action="{{url('admin/add_jersey')}}" method="post" id="addJearcyForm"   enctype="multipart/form-data">
                <div class="modal-body">
                     <div class="edit-heading-popup">
                        <h4>Add Jearcy</h4>
                    </div>
                    <input type="hidden" name="id" value="" class="jerseyId">
                    @csrf 
                        <div class="form-group">
                        <label for="name">Country:</label>
                       
                        <select class="form-control import-field" id="country_id" name="country_id" required style="width: 301px;">
                            <option value="">Select Country</option>
                            @foreach($allCountry as $row)
                            <option value="{{ $row->id }}">{{ $row->name }}</option>
                            @endforeach
                        </select>
                        
                        </div>
                        <div class="form-group">
                        <label for="">Jersey Color:</label>
                        <input type="color" class="form-control import-field" id="jersey_color" name="jersey_color" required style="width: 301px;">
                        </div>
                         <div class="form-group">
                        <label for="">year:</label>
                        <input type="number" class="form-control import-field" id="year" name="year" required style="width: 301px;">
                        </div>
                         <div class="form-group">
                        <label for="name">Type:</label>
                    
                        <select class="form-control import-field" id="type" name="type" required style="width: 301px;">
                            <option value="">Select Type</option>
                           <option value="0">Player</option>
                           <option value="1">GoalKeeper</option>
                           <option value="2">Gold</option>
                           <option value="3">Purple</option>
                           <option value="4">Red</option>
                           <option value="5">Gold(Gk)</option>
                           <option value="6">Purple(Gk)</option>
                           <option value="7">Red(Gk)</option>
                        </select>
</div>
                        <div class="form-group">
                        <label for="message">Jersey Image:</label>
                        <input class="import-field" id="jersey_image" name="jersey_image" type="file" placeholder="User Name">
                        </div>
                        <button type="submit" class="logout-in-btn">Save</button>
                  
                </div>
                </form>
                        
                </div>
                </div>
                </div>
    <!-- edit jercy modal -->
      <div class="modal fade" id="editjearcyModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-edit" role="document">
            <div class="modal-content clearfix">

                <div class="modal-heading">
                    <button type="button" class="close close-btn" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>

                <div class="modal-body">

                    <div class="edit-heading-popup">
                        <h4>Edit Jearcy</h4>
                    </div>

                    <div class="edit-in">

                        <form id="editJearcyForm" enctype="multipart/form-data">

                            @csrf

                            <input type="hidden" id="jearcy_id" name="jearcy_id">

                            <!-- changes -->
                              <div class="form-group">
                        <label for="name">Country:</label>
                       
                        <select class="form-control import-field" id="country_id" name="country_id" required style="width: 301px;">
                            <option value="">Select Country</option>
                            @foreach($allCountry as $row)
                            <option value="{{ $row->id }}">{{ $row->name }}</option>
                            @endforeach
                        </select>
                        
                        </div>
                        <div class="form-group">
                        <label for="email">Jersey Color:</label>
                        <input type="color" class="form-control import-field" id="jersey_color" name="jersey_color" required style="width: 301px;">
                        </div>
                         <div class="form-group">
                        <label for="">year:</label>
                        <input type="number" class="form-control import-field" id="year" name="year" required style="width: 301px;">
                        </div>
                         <div class="form-group">
                        <label for="name">Type:</label>
                    
                        <select class="form-control import-field" id="type" name="type" required style="width: 301px;">
                            <option value="">Select Type</option>
                           <option value="0">Player</option>
                           <option value="1">GoalKeeper</option>
                           <option value="2">Gold</option>
                           <option value="3">Purple</option>
                           <option value="4">Red</option>
                            <option value="5">Gold(Gk)</option>
                           <option value="6">Purple(Gk)</option>
                           <option value="7">Red(Gk)</option>
                        </select>
</div>
                        <div class="form-group">
                        <label for="message">Jersey Image:</label>
                        <input class="import-field" id="jersey_image" name="jersey_image" type="file" placeholder="User Name">
                        </div>
                        <button type="submit" class="logout-in-btn">update</button>
                            <!-- changes -->

                            
                             
                            <!-- <input class="login-btn edit-in_btn" type="submit" value="Update"> -->

                        </form>

                    </div>

                </div>

            </div>
        </div>
    </div>
    <!-- edit jercy modal -->

		 <!-- delete popup -- -->

		 <div class="modal fade" id="delete-query" tabindex="-1"role="dialog"aria-labelledby="myModalLabel">
			<div class="modal-dialog modal-dialog-edit"role="document">
				<div class="modal-content clearfix">
					<div class="modall-head">
						<button type="button" class="close close-btn-front" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">×</span>
						</button>
					</div>
					<div class="modal-body-2">
						<div class="small-box">
							<form id="delete_form" method="post">
                                @csrf
                                <input type="hidden" name="jearcy_id" id="delete_jearcy_id" value="">
                                <div class="content-box">
                                    <img src="images/roam-images/delete-popup.png" alt="">
                                    <h3 class="heading-delete">Delete!</h3>
                                    <p class="para-2">Are you sure you want to delete ?</p>
                                </div>
                                <div>
                                    {{-- <button  type="submit" class="confirm-btn" data-dismiss="modal"aria-label="Close">Confirm</a> --}}
                                    <button type="submit" class="confirm-btn" >Confirm</button>
                                </div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- delete popup -- -->
         <script>
             function conform_delete(jearcy)
            {
                // alert(jearcy);
                $('#delete-query').modal('show');
                $('#delete_jearcy_id').val(jearcy);

            }
            $("#delete_form").submit(function(e){
                e.preventDefault();
                let formData = new FormData(this);

                // for (const pair of formData.entries()) {
                // console.log(`${pair[0]}, ${pair[1]}`);
                // }
                // return false;

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                // cache: false,
                url:'{{route("admin.delete_jurcy")}}',
                type: 'POST',
                dataType: 'json',
                data: formData,//form.serialize()
                processData: false,
                contentType: false,
                success: function (res) {
                if(res.status == 200)
                {
                    toastr.success('success..!');
                    setTimeout(() => {
                        window.location.reload();
                    }, 3000);
                }else{
                    $.each(res.errors, function (field_name,error) {
                    toastr.error(error);
                    });
                }
                },
                error: function (result) {
                    alert(result);
                }
                });
            })
            //add jercy js
            $('#addJearcyForm').submit(function(e) {

    e.preventDefault();

    var formData = new FormData(this);

    $.ajax({
        url: "{{ url('admin/add_jersey') }}",
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,

        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },

        success: function(res) {

            if (res.status == 200) {

                toastr.success(res.message);

                $('#addModal').modal('hide');

                setTimeout(function() {
                    location.reload();
                }, 1000);

            } else {

                toastr.error(res.message);

            }

        },

        error: function(xhr) {

            if (xhr.status == 422) {

                $.each(xhr.responseJSON.errors, function(key, value) {
                    toastr.error(value[0]);
                });

            } else {

                toastr.error("Something went wrong.");

            }

        }

    });

});
            //add jercy js

            //edit button 

           function edit_jearcy(id)
{
    $.ajax({
        url:"{{url('admin/edit_jersey')}}/"+id,
        type:"GET",
        success:function(res){

            $('#editjearcyModal').modal('show');

            $('#jearcy_id').val(res.data.id);
            $('#editjearcyModal select[name="country_id"]').val(res.data.country_id);
            $('#editjearcyModal input[name="jersey_color"]').val(res.data.jersey_color);
            $('#editjearcyModal input[name="year"]').val(res.data.year);
            $('#editjearcyModal select[name="type"]').val(res.data.type);
        }
    });
}
       //update jearcy
       $("#editJearcyForm").submit(function(e){

    e.preventDefault();

    var formData = new FormData(this);

    $.ajax({

        url:"{{url('admin/update_jersey')}}",
        type:"POST",
        data:formData,
        processData:false,
        contentType:false,

        success:function(res){

            if(res.status==200){

                toastr.success(res.message);

                $('#editjearcyModal').modal('hide');

                setTimeout(function(){
                    location.reload();
                },1000);

            }

        }

    });

});  

            //change
		$(document).ready(function(){

    $('#search').on('keyup', function(){
        loadStatistics();
    });

});

function loadStatistics()
{
    $.ajax({
        url: "{{ route('admin.jersey_Management') }}", // agar route name hai
        type: "GET",
        data: {
            search: $('#search').val()
        },
        success: function(response){

            $('#jersey').html(
                $(response).find('#jersey').html()
            );

        },
        error: function(xhr){
            console.log(xhr.responseText);
        }
    });
}
		//change
         </script>
        
    

    @endsection
