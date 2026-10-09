    @extends('layouts.app')
	@section('title','Statistics Management')
    @section('content')
    <main>
				<div class="influ-strip-2">
					<div class="influ-search">
						<form>
							{{-- <label for="">
								<input type="text" name="" placeholder="Search by Sur name" id="search">
								<button><img src="images/search.png" alt=""></button>
							</label> --}}
						</form>
					</div>
					<div class="influ-btns">

						<form id="statistic_filter">
							<div class="influ-search">

							<!-- 1 -->
                            <label for="">
								<input type="text" name="search" placeholder="Search by Player Name " id="search" value="{{$request->search??''}}">
								<button><img src="{{asset('assets/images/search-jb.png')}}" alt=""></button>
							</label>
							</div> 

							<!-- 2 -->

							<a href="javascript:void(0)"class="influ-btn" onclick="export_csv()"><img src="{{asset('assets/images/exporticon.svg')}}" alt=""> Export CSV </a>

					</div>
				</div>
				<div class="influ-table" id="statistics">
					<div id="table-responsive-1" class="table-responsive">

						<table>
							<tr>
								<th>S. No.</th>
								<th>Playername</th>
								<th>Total no. of games played</th>
								<th>Goal scored</th>
								<th>Goal concede</th>
								<th>Win percentage</th>
								<th>Total points</th>
								<th>Worldcups won</th>

							</tr>
							@php
								$i = $players->perPage() * ($players->currentPage() - 1);
							@endphp
                            @forelse ($players as $key=>$player)
                                <!-- <tr> -->
                                    <!-- <td>{{++$i}}.</td>
                                    <td><a href="#" class="players-anchor">{{$player->name}}</a> </td>
                                    <td>04</td>
                                    <td>14</td>
                                    <td>09</td>
                                    <td>63%</td>
                                    <td>99</td>
                                    <td>04</td> -->

                                <!-- </tr> -->
                            @empty
                            <tr>
                                <td colspan="10">Data not found</td>
                            </tr>

                            @endforelse


							{{-- <tr>
								<td>2.</td>
								<td><a href="#">John</a></td>
								<td>44</td>
								<td>44</td>
								<td>11</td>
								<td>44%</td>
								<td>55</td>
								<td>01</td>

							</tr>
							<tr>
								<td>3.</td>
								<td> <a href="#">Robbin </a></td>
								<td>24</td>
								<td>66</td>
								<td>06</td>
								<td>66%</td>
								<td>88</td>
								<td>06</td>

							</tr>
							<tr>
								<td>4.</td>
								<td><a href="#">Stacy </a></td>
								<td>30</td>
								<td>24</td>
								<td>22</td>
								<td>55%</td>
								<td>69</td>
								<td>09</td>

							</tr>
							<tr>
								<td>5.</td>
								<td><a href="#">jason </a></td>
								<td>18</td>
								<td>48</td>
								<td>15</td>
								<td>48%</td>
								<td>78</td>
								<td>02</td>

							</tr>
							<tr>
								<td>6.</td>
								<td><a href="#">Andrew </a></td>
								<td>22</td>
								<td>55</td>
								<td>06</td>
								<td>55%</td>
								<td>98</td>
								<td>00</td>

							</tr>
							<tr>
								<td>7.</td>
								<td><a href="#">Tommy</a> </td>
								<td>29</td>
								<td>30</td>
								<td>30</td>
								<td>76%</td>
								<td>69</td>
								<td>06</td>

							</tr>
 --}}





						</table>
					</div>
					<!-- <p>Showing 50 of 170 results</p> -->
				{{-- <div class="pagination">
					<div class="influ-pagi">
						<ul>
							<li><a href="#"><i>Prev</i></a></li>
							<li class="active"><a href="#">1</a></li>
							<li><a href="#">2</a></li>
							<li><a href="#">3</a></li>
							<li><a href="#">4</a></li>
							<li><a href="#">5</a></li>
							<li><a href="#"><i>Next</i></a></li>
						</ul>
					</div>
					<form action="">
						<div class="showing-1">
							<p>Showing 50 of 170 results</p>
						</div>
					</form>
				</div> --}}
				{!! $players->onEachSide(1)->links('layouts.paginate') !!}

				</div>
			</main>


     <!-- delete popup -- -->

		 <div class="modal fade" id="delete-query" tabindex="-1"role="dialog"aria-labelledby="myModalLabel">
			<div class="modal-dialog modal-dialog-edit"role="document">
				<div class="modal-content clearfix">
					<div class="modall-head">
						<button type="button"class="close close-btn-front"data-dismiss="modal"aria-label="Close">
						<span aria-hidden="true">×</span>
						</button>
					</div>
					<div class="modal-body-2">
						<div class="small-box">
							<form>
								<div class="content-box">
								<img src="images/roam-images/delete-popup.png" alt="">
								<h3 class="heading-delete">Delete!</h3>
								<p class="para-2">Are you sure you want to delete ?</p>
								</div>

								<div>
									<a href="#" type="submit" class="confirm-btn" data-dismiss="modal"aria-label="Close">Confirm</a>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>

		<!-- delete popup -- -->

        <script>
        // $('#statistic_filter').onclick(function(){
        //     // filterSearchReportManage();
        //     this.submit()
        // });

		//change
		$(document).ready(function(){

    $('#search').on('keyup', function(){
        loadStatistics();
    });

});

function loadStatistics()
{
    $.ajax({
        url: "{{ route('admin.statistics') }}", // agar route name hai
        type: "GET",
        data: {
            search: $('#search').val()
        },
        success: function(response){

            $('#statistics').html(
                $(response).find('#statistics').html()
            );

        },
        error: function(xhr){
            console.log(xhr.responseText);
        }
    });
}
		//change

        function export_csv()
        {
            console.log('gdgafsgfdag')
            let params = new URLSearchParams(document.location.search);
            let newParams = params.toString()
            	console.log('ghghghghghg',newParams);
            if(newParams !=''){
                window.location.href = '/admin/statistics_export_csv?'+newParams;
            }else{
                window.location.href = '/admin/statistics_export_csv';
            }

        }

		$(document).mouseup(function(e) 
        {
            var container = $(".hile-card");

            // if the target of the click isn't the container nor a descendant of the container
            if (!container.is(e.target) && container.has(e.target).length === 0) 
            {
                container.hide();
            }
        });
       

        </script>

    @endsection
