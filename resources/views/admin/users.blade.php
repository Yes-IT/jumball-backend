    @extends('layouts.app')
    @section('title', 'User Management')
    @section('content')
        <main>
            <div class="influ-strip-2">
                <div class="influ-search">
                    <form>
                        <label for="">
                            <input type="text" name="search" placeholder="Search by name or email" id="search"
                                value="{{ $request->search ?? '' }}">
                            <button type="button" onclick="user_manage_search()"><img
                                    src="{{ asset('assets/images/search-jb.png') }}" alt=""></button>
                        </label>
                    </form>
                </div>
                <div class="influ-btns">
                    <!-- <div class="influ-search">
                                    <label for="">
                                        <input type="text" name="search" placeholder="Search" value="{{ $request->search ?? '' }}">
                                        <button><img src="{{ asset('assets/images/search-jb.png') }}" alt=""></button>
                                    </label>
                                </div>    -->
                    <form id="user_manage_fillter">

                        <input type="hidden" name="search" value="{{ $request->search ?? '' }}">
                        <!-- 1 -->
                        <!-- <div class="quotes">
                                        <div id="dropdown-btn"class="dropdown-btn">Employer <i class="far fa-chevron-down"></i></div>
                                        <div id="dropdown"class="dropdown"style="display: none">
                                            <div class="search-in">
                                                <label>
                                                    <input class="search_quote"id="search_quote"type="text">
                                                    <i class="fal fa-search"></i>
                                                </label>
                                            </div>
                                            <div class="quotes-list">
                                                <div id="search-value"class="search-value">
                                                    <label class="influ-btns-label"><input type="checkbox"value="">Employer 1</label>
                                                </div>
                                                <div id="search-value"class="search-value">
                                                    <label class="influ-btns-label"><input type="checkbox"value="">Employer 2</label>
                                                </div>
                                                <div id="search-value"class="search-value">
                                                    <label class="influ-btns-label"><input type="checkbox"value="">Employer 3</label>
                                                </div>
                                                <div id="search-value"class="search-value">
                                                    <label class="influ-btns-label"><input type="checkbox"value="">Employer 4</label>
                                                </div>
                                                <div id="search-value"class="search-value">
                                                    <label class="influ-btns-label"><input type="checkbox"value="">Employer 5</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div> -->
                        <!-- 2 -->
                        <!-- <div class="quotes">
                                        <div id="dropdown-btn-1"class="dropdown-btn">Carrier <i class="far fa-chevron-down"></i></div>
                                        <div id="dropdown-1"class="dropdown"style="display: none">
                                            <div class="search-in">
                                                <label>
                                                    <input class="search_quote"id="search_quote-1"type="text">
                                                    <i class="fal fa-search"></i>
                                                </label>
                                            </div>
                                            <div class="quotes-list">
                                                <div id="search-value-1"class="search-value">
                                                    <label class="influ-btns-label"><input type="checkbox"value="">Partner 01</label>
                                                </div>
                                                <div id="search-value-1"class="search-value">
                                                    <label class="influ-btns-label"><input type="checkbox"value="">Partner 02</label>
                                                </div>
                                                <div id="search-value-1"class="search-value">
                                                    <label class="influ-btns-label"><input type="checkbox"value="">Partner 03</label>
                                                </div>
                                                <div id="search-value-1"class="search-value">
                                                    <label class="influ-btns-label"><input type="checkbox"value="">Partner 04</label>
                                                </div>
                                                <div id="search-value-1"class="search-value">
                                                    <label class="influ-btns-label"><input type="checkbox"value="">Partner 05</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div> -->
                        <!-- 3 -->

                        <div class="quotes">
                            <div id="dropdown-btn-2"class="dropdown-btn">Skill <i class="far fa-chevron-down"></i></div>
                            <div id="dropdown-2"class="dropdown "style="display: none">

                                            <div class="quotes-list">
                                                <div id="search-value-2 "class=" search-value">
                                <label class="influ-btns-label"><input type="checkbox" name="skill_level[]" value="Amateur"
                                        {{ !empty($request->skill_level) && in_array('Amateur', $request->skill_level) ? 'checked' : '' }}>Amateur</label>
                            </div>
                            <div id="search-value-2"class="search-value">
                                <label class="influ-btns-label"><input type="checkbox" name="skill_level[]"
                                        value="Professional"
                                        {{ !empty($request->skill_level) && in_array('Professional', $request->skill_level) ? 'checked' : '' }}>Professional</label>
                            </div>
                            <div id="search-value-2"class="search-value">
                                <label class="influ-btns-label"><input type="checkbox" name="skill_level[]"
                                        value="International"
                                        {{ !empty($request->skill_level) && in_array('International', $request->skill_level) ? 'checked' : '' }}>
                                    International</label>
                            </div>
                        </div>
                </div>
            </div>
            <!-- 4 -->
            <div class="quotes">
                <div id="dropdown-btn-3"class="dropdown-btn"> Position <i class="far fa-chevron-down"></i></div>
                <div id="dropdown-3"class="dropdown" style="display: none">
                    <!-- <div class="search-in">
                                                <label>
                                                    <input class="search_quote"id="search_quote-3"type="text">
                                                    <i class="fal fa-search"></i>
                                                </label>
                                            </div> -->
                    <div class="quotes-list">
                        <div id="search-value-3"class="search-value">
                            <label class="influ-btns-label"><input type="checkbox"name="position[]" value="GK"
                                    {{ !empty($request->position) && in_array('Gk', $request->position) ? 'checked' : '' }}>Goalkeeper</label>
                        </div>
                        <div id="search-value-3"class="search-value">
                            <label class="influ-btns-label"><input type="checkbox"name="position[]" value="DF"
                                    {{ !empty($request->position) && in_array('DF', $request->position) ? 'checked' : '' }}>Defender</label>
                        </div>

                        <div id="search-value-3"class="search-value">
                            <label class="influ-btns-label"><input type="checkbox"name="position[]" value="MF"
                                    {{ !empty($request->position) && in_array('MiF', $request->position) ? 'checked' : '' }}>Midfielder</label>
                        </div>
                        <div id="search-value-3"class="search-value">
                            <label class="influ-btns-label"><input type="checkbox"name="position[]" value="ST"
                                    {{ !empty($request->position) && in_array('ST', $request->position) ? 'checked' : '' }}>Striker</label>
                        </div>

                    </div>
                </div>
            </div>
            <!-- 5 -->
            <div class="quotes">
                <div id="dropdown-btn-4"class="dropdown-btn">Style of play <i class="far fa-chevron-down"></i></div>
                <div id="dropdown-4"class="dropdown "style="display: none">
                                            <!-- <div class="search-in">
                                                <label>
                                                    <input class="search_quote"id=" search_quote-4"type="text">
                    <i class="fal fa-search"></i>
                    </label>
                </div> -->
                <div class="quotes-list">
                    <div id="search-value-4"class="search-value"><label class="influ-btns-label"><input type="checkbox"
                                name="play_style[]" value="Defensive"
                                {{ !empty($request->play_style) && in_array('Defensive', $request->play_style) ? 'checked' : '' }}>Defensive</label>
                    </div>
                    <div id="search-value-4" class="search-value"><label class="influ-btns-label"><input type="checkbox"
                                name="play_style[]" value="Balanced"
                                {{ !empty($request->play_style) && in_array('Balanced', $request->play_style) ? 'checked' : '' }}>Balanced
                        </label></div>
                    <div id="search-value-4"class="search-value"><label class="influ-btns-label"><input type="checkbox"
                                name="play_style[]" value="Attacking"
                                {{ !empty($request->play_style) && in_array('Attacking', $request->play_style) ? 'checked' : '' }}>Attacking</label>
                    </div>


                </div>
            </div>
            </div>
            <!-- 6 -->
            <div class="broker-date">
                <div>
                    <input type="text" name="datefilter" value="{{ request()->datefilters }}" id="datefilter"
                        placeholder="Date Range" readonly>
                    <input type="hidden" name="datefilters" id="datefilterhidden"
                        value="{{ request()->datefilters }}">
                </div>

                <i class="far fa-chevron-down"></i>

            </div>
            <!-- <button type="button"class="show-modal influ-btn"data-toggle="modal"data-target="#myModal-5">More Filters <img src="images/filter.png"alt=""></button> -->
            <button type="button" class="influ-btn" onclick="export_csv()"><img
                    src="{{ asset('assets/images/exporticon.svg') }}" alt=""> Export CSV </button>
            </form>
            </div>
            </div>
            <div class="influ-table" id="userTable">
                <div id="table-responsive-1"class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>S. No.</th>
                                <th>User Name</th>
                                <th>Profile Picture</th>
                                <th>Email</th>
                                <th>Country</th>
                                <th>Skill Level</th>
                                <th>Position</th>
                                <th>Style of Play</th>
                                <th>World Cup</th>
                                <th>Signup Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="userBody">
                            @php
                                $i = $users->perPage() * ($users->currentPage() - 1);
                            @endphp
                            @forelse ($users as $key=>$user)
                                <tr>
                                    <td>{{ ++$i }}.</td>
                                    <td>{{ $user->name }}</td>
                                    <td class="pdf-icn">
                                        <img src="{{ !empty($user->profile_images) ? asset('assets/images/' . $user->profile_images) : asset('assets/images/profilepic.svg') }}"
                                            alt="">
                                    </td>
                                    <td>{{ $user->email ?? '' }}</td>
                                    {{-- <td>{{$user->Country->name??''}}</td> --}}
                                    <td>{{ $user->country_name }}</td>
                                    <td>{{ $user->skill_level ?? '' }}</td>
                                    @php
                                        $position = '';
                                        if ($user->position == 'MF') {
                                            $position = 'Midfielder';
                                        } elseif ($user->position == 'DF') {
                                            $position = 'Defender';
                                        } elseif ($user->position == 'GK') {
                                            $position = 'Goalkeeper';
                                        } else {
                                            $position = $user->position;
                                        }
                                    @endphp
                                    <td>{{ $position ?? '' }}</td>
                                    <td>{{ $user->play_style ?? '' }}</td>
                                    <td>{{ $user->WorldCup->Country->name ?? '' }} {{ $user->WorldCup->year ?? '' }}</td>

                                    <td>{{ date('d/m/Y', strtotime($user->created_at)) }}</td>
                                    <td>
                                        {{-- <a type="button"class="show-modal"data-toggle1="modal" data-target1="#myModal-2" onclick="edit('{{$user->id}}')">
                                    <img src="{{asset('assets/images/edit.png')}}" alt="">
                                </a> --}}
                                        @if ($user->status == 1)
                                            <a type="button"class="show-modal" data-toggle="modal"
                                                data-target1="#myModal-13"
                                                onclick="status_change('{{ $user->id }}','{{ $user->status }}')">
                                                <img src="{{ asset('assets/images/disable.png') }}" alt="">
                                            </a>
                                        @else
                                            <a type="button"class="show-modal" data-toggle="modal"
                                                data-target1="#myModal-13"
                                                onclick="status_change('{{ $user->id }}','{{ $user->status }}')">
                                                <img src="{{ asset('assets/images/check.png') }}" alt="">
                                            </a>
                                        @endif
                                        <a type="button" class="show-modal" data-toggle="modal"
                                            data-target11="#delete-query11" data-id="{{ $user->id }}"
                                            onclick="delete_conform('{{ $user->id }}')">
                                            <img src="{{ asset('assets/images/remove.png') }}" alt="">
                                        </a>


                                        <!-- <a href=""><img src="images/disable.png" alt=""></a> -->
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10">Data not found</td>
                                </tr>
                            @endforelse
                        </tbody>

                    </table>
                </div>
                {!! $users->onEachSide(1)->links('layouts.paginate') !!}
                <!-- <p>Showing 50 of 170 results</p> -->
                <!-- <div class="influ-pagi">
                                <ul>
                                    <li><a href="#"><i>Prev</i></a></li>
                                    <li class="active"><a href="#">1</a></li>
                                    <li><a href="#">2</a></li>
                                    <li><a href="#">3</a></li>
                                    <li><a href="#">4</a></li>
                                    <li><a href="#">5</a></li>
                                    <li><a href="#"><i>Next</i></a></li>
                                </ul>
                                <form action="">
                                    <div class="influ-pagi-in">
                                        <p>Showing 50 of 170 results</p>
                                    </div>
                                </form>
                            </div> -->
            </div>
        </main>



        <div class="modal fade"id="myModal-2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog modal-dialog-edit"role="document">
                <div class="modal-content clearfix">
                    <div class="modal-heading">
                        <button type="button"class="close close-btn" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>

                    </div>
                    <div class="modal-body">
                        <div class="edit-heading-popup">
                            <h4>Edit User</h4>
                        </div>
                        <div class="edit-in">
                            <form>
                                <input class="edit-in_txt-2" type="text" name="" placeholder="User Name">
                                <input class="edit-in_txt-2" type="text" name=""
                                    placeholder="Profile picture">
                                <input class="edit-in_txt-2" type="text" name="" placeholder="Email ">
                                <input class="edit-in_txt-2" type="text" name="" placeholder="Country">
                                <input class="edit-in_txt-2" type="text" name="" placeholder="Skill level">
                                <input class="edit-in_txt-2" type="text" name="" placeholder="Position ">
                                <input class="edit-in_txt-2" type="text" name="" placeholder="Style of play">
                                <input class="edit-in_txt-2" type="text" name=""
                                    placeholder="Profile picture">

                                {{-- <input class="login-btn edit-in_btn" type="button" value="Edit"> --}}
                                <input class="login-btn edit-in_btn" type="button" value="Save">
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- SUSPEND-USER-POPUP -->
        <div class="modal fade"id="myModal-13" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog modal-dialog-edit"role="document">
                <div class="modal-content clearfix">
                    <div class="modal-heading">
                        <button type="button"class="close close-btn" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>

                    </div>
                    <div class="modal-body">

                        <div class="common-heading-popup">
                            <h4 id="msg_block_head">Suspend User</h4>
                        </div>
                        <div class="logout-in">
                            <p id="msg_block_body">Please confirm you want to suspend the access of the selected user to
                                Jumball.</p>
                            <form id="status_change_form">
                                <input type="hidden" name="user_id" id="user_id_4_status">
                                <button type="submit" class="show-modal logout-in-btn logout-in-btn-red">Confirm</button>
                                <!-- <a type="button" class="show-modal logout-in-btn logout-in-btn-red" data-toggle="modal" data-target="#myModal-140">Confirm</a> -->
                                <a href="" type="button"class="logout-in-btn" data-dismiss="modal"
                                    aria-label="Close">Cancel</a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- SUSPEND-USER-POPUP -->
        <!-- SUSPEND-SUCCESS-POPUP -->
        <div class="modal fade" id="myModal-14" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog modal-dialog-edit" role="document">
                <div class="modal-content clearfix">
                    <div class="modal-heading">
                        <button type="button" class="close close-btn" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                        <!-- <h1>Add Category</h1> -->
                    </div>
                    <div class="modal-body">
                        <div class="logout-in">
                            <h1>Success!</h1>
                            <p>The selected user is suspended from Jumball.</p>
                            <form>
                                <a type="button" class="logout-in-btn logout-in-btn-red" data-dismiss="modal"
                                    aria-label="Close">Ok</a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- SUSPEND-USER-POPUP -->

        <!-- delete popup ---->

        <div class="modal fade" id="delete-query" tabindex="-1"role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog modal-dialog-edit"role="document">
                <div class="modal-content clearfix">
                    <div class="modall-head">
                        <button type="button"class="close close-btn-front" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body-2">
                        <div class="small-box">
                            <form id="delete_form">
                                <div class="content-box">
                                    <img src="images/roam-images/delete-popup.png" alt="">
                                    <h3 class="heading-delete">Delete!</h3>
                                    <p class="para-2">Are you sure you want to delete ?</p>
                                </div>
                                <input type="hidden" name="user_id" id="user_id" value="">

                                <div>
                                    <button type="submit" class="confirm-btn">Confirm</button>
                                    {{-- <button type="submit" class="confirm-btn" data-dismiss="modal"aria-label="Close">Confirm</button> --}}
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- delete popup -- -->



        <script>
            $(document).ready(function() {
                // toastr.success('You clicked Success toast');
                let params = new URLSearchParams(document.location.search);
                // console.log(params.getAll());
                console.log(params.get('datefilter'));
            })

            //changes
            $(document).ready(function() {

                $('#search').on('keyup', function() {
                    user_manage_search();
                });

            });



            function loadUsers() {

                let data = $('#user_manage_fillter').serialize();
                data += '&search=' + $('#search').val();

                $.ajax({
                    url: "{{ url('/admin/users') }}",
                    type: "GET",
                    data: data,

                    success: function(response) {

                        $('#userTable').html(
                            $(response).find('#userTable').html()
                        );

                    },

                    error: function(xhr) {
                        console.log(xhr.responseText);
                    }

                });

            }
            //changes



            // $('#user_manage_fillter').change(function(){
            //     // filterSearchReportManage();
            //     this.submit()
            // });

            // $('#user_manage_search').onclick(function(){
            // function user_manage_search() {
            //     let search =  $('#search').val();
            //     if (window.location.href.indexOf("?") > -1) {
            //         if (window.location.href.indexOf("search") > -1) {
            //             let params = new URLSearchParams(document.location.search);
            //             params.set("search", search);
            //             let newparam = params.toString();
            //             // console.log('newparam',newparam);
            //             window.location.href = '/admin/users?'+newparam;
            //         }else{
            //             window.location.href = `?search=${search}`;
            //         }
            //     }else{
            //         window.location.href = `?search=${search}`;
            //     }
            // };

            //changes
            //            function user_manage_search()
            // {
            //     let search = $('#search').val();

            //     $.ajax({
            //         url: "{{ url('/admin/users') }}",
            //         type: "GET",
            //         data: {
            //             search: search
            //         },
            //         success: function(response) {

            //             var newHtml = $(response).find('#userTable').html();

            //             if(newHtml != undefined){
            //                 $('#userTable').html(newHtml);
            //             }else{
            //                 console.log("userTable not found");
            //             }
            //         },
            //         error: function(xhr){
            //             console.log(xhr.responseText);
            //         }
            //     });
            // }

            function user_manage_search() {
                loadUsers();
            }

            $('#user_manage_fillter input[type="checkbox"]').on('change', function() {

                loadUsers();

            });
            //changes

            // $('#datefilter').onclick(function(){
            //     $('.hile-card').css('display','none');
            // })
            // $('input[name="datefilter"]').on('apply.daterangepicker', function(ev, picker) {
            //     var date = $('.drp-selected').text();
            //     let oo = $('#datefilter').data('daterangepicker').startDate.format('MM/DD/YYYY');
            //     let ooo = $('#datefilter').data('daterangepicker').endDate.format('MM/DD/YYYY');
            //     console.log(oo+'-'+ooo);
            //     $('#datefilterhidden').val(oo+'-'+ooo);
            //     $('#user_manage_fillter').submit();

            // })

            //change
            $('input[name="datefilter"]').on('apply.daterangepicker', function() {

                let from = $('#datefilter').data('daterangepicker').startDate.format('MM/DD/YYYY');

                let to = $('#datefilter').data('daterangepicker').endDate.format('MM/DD/YYYY');

                $('#datefilterhidden').val(from + '-' + to);

                loadUsers();

            });
            //change

            // $('input[name="datefilter"]').on('cancel.daterangepicker', function(ev, picker) {
            //     $('#datefilter').val('');
            //     $('#datefilterhidden').val('');
            //     $('#user_manage_fillter').submit();

            // })
            //changes
            $('input[name="datefilter"]').on('cancel.daterangepicker', function() {

                $('#datefilter').val('');

                $('#datefilterhidden').val('');

                loadUsers();

            });
            //changes
            // $('input[name="datefilter"]').on('apply.daterangepicker', function(ev, picker) {

            //     this.submit()
            //     var date = $('.drp-selected').text();
            //     let oo = $('#datefilter').data('daterangepicker').startDate.format('MM/DD/YYYY');
            //     let ooo = $('#datefilter').data('daterangepicker').endDate.format('MM/DD/YYYY');

            //     $("#datefilter").datepicker("option", "minDate", oo);
            //     $("#datefilter").datepicker("option", "maxDate", ooo);

            //     //==============================================================================
            //     let params = new URLSearchParams(document.location.search);
            //    let  str1 =   document.location.href;
            //    let  str2 =   '?';
            //     let question_mark = false;
            //     if(str1.indexOf(str2) != -1){
            //         question_mark = true;
            //     }

            //     if(!question_mark)
            //     {
            //         window.location.href = str1+'?datefilter='+oo+'-'+ooo;
            //         // window.location.href = '?search=&datefilter='+oo+'-'+ooo+'&skill_level=&position=&palay_style=';
            //     }
            //     else
            //     {
            //         if(params.get('datefilter'))
            //         {

            //             params.set('datefilter',oo+'-'+ooo)
            //             let newParams = params.toString()
            //             console.log('ghghghghghg',newParams);
            //             // window.location.href = '/admin/users?'+newParams;
            //         }else{

            //             window.location.href = '/admin/users?'+'&datefilter='+oo+'-'+ooo;
            //         }

            //     }

            // });

            function status_change(id, status) {
                // console.log('id',id)

                if (status == 0) {
                    $('#msg_block_head').html('Suspend User');
                    $('#msg_block_body').html('Please confirm you want to suspend the access of the selected user to Jumball.');
                } else {
                    $('#msg_block_head').html('Active User');
                    $('#msg_block_body').html('Please confirm you want to active the access of the selected user to Jumball.');
                }

                $('#myModal-13').modal('show');

                $('#user_id_4_status').val(id);

            }

            $('#status_change_form').submit(function(e) {
                e.preventDefault();
                // console.log('hdfgh');
                let formData = new FormData(this);
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    // cache: false,
                    url: '{{ route('admin.user_status_change') }}',
                    type: 'POST',
                    dataType: 'json',
                    data: formData, //form.serialize()
                    processData: false,
                    contentType: false,
                    success: function(res) {
                        if (res.status == 200) {
                            toastr.success('success..!');
                            setTimeout(() => {
                                window.location.reload();
                            }, 3000);
                        } else {
                            $.each(res.errors, function(field_name, error) {
                                toastr.error(error);
                            });
                        }
                    },
                    error: function(result) {
                        alert(result);
                    }
                });
            })

            function edit(id) {
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: 'get',
                    url: "/admin/edit_user_profile/" + id,
                    dataType: "json",
                    success: function(resp) {
                        if (resp.data) {
                            console.log(resp.data.user);
                            let user = resp.data.user;
                            let country = user.country;
                            let world_cup = user.world_cup;
                            let countries = resp.data.countries;
                            console.log('country', country);
                            let html = '';
                            html += `
                            <form id="edit_form1">
                                <input type="hidden" name="user_id" id="user_id" value="${user.id}">
								<input class="edit-in_txt-2" type="text" id="name" name="name" placeholder="User Name" value="${user.name}">
								<input class="edit-in_txt-2 file-input" type="file" id="image_profile" name="image_profile" placeholder="Profile picture">
                            
                                <input class="edit-in_txt-2" type="text" id="country_name" name="country_name" placeholder="Country" name="country_name" value="${user.country_name}">
                                
                                <select class="edit-in_txt-2" id="skill_level" name="skill_level" placeholder="Skill level" name="Skill_level" >
                                    <option value="Amateur" ${'Amateur'==user.skill_level?'selected':''}>Amateur</option>                       
                                    <option value="Professional" ${'Professional'==user.skill_level?'selected':''}>Professional</option>                       
                                    <option value="International" ${'International'==user.skill_level?'selected':''}>International</option>                       
                                </select>

                                <select class="edit-in_txt-2" id="position" name="position" placeholder="Position " name="position"  >
                                    <option value="Striker" ${'Striker'==user.position?'selected':''}>Striker</option>                       
                                    <option value="MidFielder" ${'MidFielder'==user.position?'selected':''}>MidFielder</option>                       
                                    <option value="Defender" ${'Defender'==user.position?'selected':''}>Defender</option>                       
                                </select>

                                <select class="edit-in_txt-2" id="play_style" name="play_style" placeholder="Style of play" name="play_style" >
                                    <option value="Attacking" ${'Attacking'==user.play_style?'selected':''}>Attacking</option>                       
                                    <option value="Balanced" ${'Balanced'==user.play_style?'selected':''}>Balanced</option>                       
                                    <option value="Defensive" ${'Defensive'==user.play_style?'selected':''}>Defensive</option>                       
                                </select>
                                `

                            html += `<button type="button" class="login-btn edit-in_btn" onclick="save_issue()">Save</button>

							</form>
                            `;
                            $('.edit-in').html(html)
                            $('#myModal-2').modal('show')
                        } else {
                            console.log('Error')
                        }
                    }
                });
            }

            // function delete_profile(id)
            // {
            //     $.ajax({
            //         headers: {
            //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            //         },
            //         type: 'get',
            //         url: "/admin/delete_user_profile/"+id,
            //         dataType: "json",
            //         success: function(resp) {
            //             if (resp.data) {
            //                 $('#delete-query').modal('show');
            //             } else {
            //                 console.log('Error');
            //             }
            //         }
            //     });
            // }

            function delete_conform(id) {
                $('#user_id').val(id);
                $('#delete-query').modal('show');
            }




            $("#delete_form").submit(function(e) {
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
                    url: '{{ route('admin.delete_user_profile') }}',
                    type: 'POST',
                    dataType: 'json',
                    data: formData, //form.serialize()
                    processData: false,
                    contentType: false,
                    success: function(res) {
                        if (res.status == 200) {
                            toastr.success('success..!');
                            setTimeout(() => {
                                window.location.reload();
                            }, 3000);
                        } else {
                            $.each(res.errors, function(field_name, error) {
                                toastr.error(error);
                            });
                        }
                    },
                    error: function(result) {
                        alert(result);
                    }
                });
            });


            // function export_csv() {

            //     let params = new URLSearchParams(document.location.search);
            //     let newParams = params.toString()
            //     // console.log('ghghghghghg', newParams);
            //     if (newParams != '') {
            //         window.location.href = '/admin/user_export_csv?' + newParams;
            //     } else {
            //         window.location.href = '/admin/user_export_csv';
            //     }

            // }

            function export_csv() {

                let data = $('#user_manage_fillter').serialize();
                data += '&search=' + $('#search').val();

                window.location.href = '/admin/user_export_csv?' + data;

            }



            $("#edit_form").submit(function(e) {
                e.preventDefault();
                // console.log('dsahjkdha');




                // return false;
            })

            function save_issue() {
                // console.log('gdhahdg');

                let name = $('#name').val();
                // let image_profile = $('#image_profile').val();
                let image_profile = $("input[type=file]").get(0).files[0];
                let country = $('#country_id').val();
                // let country = $('#country_id option:selected').text();
                let skill_level = $('#skill_level').val();
                let position = $('#position').val();
                let play_style = $('#play_style').val();
                let user_id = $('#user_id').val();
                let country_name = $('#country_name').val();

                // console.log(
                //     name,
                //     image_profile,
                //     country,
                //     skill_level,
                //     position,
                //     play_style,
                // )

                // image_profile,
                // let formData  = {
                //     user_id,
                //     name,
                //     country,
                //     skill_level,
                //     position,
                //     play_style,
                //     image_profile,
                // }


                var formData = new FormData();
                formData.append(' user_id', user_id),
                    formData.append(' name', name),
                    formData.append(' image_profile', image_profile),
                    // formData.append(' country', country),
                    formData.append(' country_name', country_name),
                    formData.append(' skill_level', skill_level),
                    formData.append(' position', position),
                    formData.append(' play_style', play_style),

                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        // cache: false,
                        url: '{{ route('admin.edit_user_profile_update') }}',
                        type: 'POST',
                        dataType: 'json',
                        data: formData, //form.serialize()
                        processData: false,
                        contentType: false,
                        success: function(res) {
                            if (res.status == 200) {
                                toastr.success('success..!');
                                setTimeout(() => {
                                    window.location.reload();
                                }, 3000);
                            } else {
                                $.each(res.errors, function(field_name, error) {
                                    toastr.error(error);
                                });
                            }
                        },
                        error: function(result) {
                            alert(result);
                        }
                    });









            }



            $(document).mouseup(function(e) {
                var container = $(".show-elipse-card");

                // if the target of the click isn't the container nor a descendant of the container
                if (!container.is(e.target) && container.has(e.target).length === 0) {
                    container.hide();
                }
            });

            $(document).mouseup(function(e) {
                var container = $(".hile-card");

                // if the target of the click isn't the container nor a descendant of the container
                if (!container.is(e.target) && container.has(e.target).length === 0) {
                    container.hide();
                }
            });
        </script>
    @endsection
