@extends('layouts.app')
  @section('title','Privacy-Policy')
    @section('content')

	<main>

        <div class="terms-wrap">

            <form id="privacy_policy">

                <!-- HEADING -->

                <h1>Privacy-Policy</h1>

                <textarea

                    placeholder="" id="description" >{{$privacy_policies->description??''}}</textarea>

                <!-- BUTTONS -->

                <div class="terms-btns">

                    <!-- <input type="submit" value="Edit" class="terms-btn"> -->

                    <input type="submit" value="Save" class="terms-btn">

                </div>

            </form>

        </div>

    </main>





     <!-- delete popup -- -->

<!-- 

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

		</div> -->



		<!-- delete popup -- -->

		<script>
let editor;

ClassicEditor
    .create(document.querySelector('#description'))
    .then(newEditor => {
        editor = newEditor;
    })
    .catch(error => {
        console.error(error);
    });

$('#privacy_policy').submit(function(e){

    e.preventDefault();

    let formData = new FormData(this);

    formData.append('description', editor.getData());

    $.ajax({

        headers:{
            'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
        },

        url:`{{ route('admin.privacy_policy_save') }}`,

        type:'POST',

        processData:false,

        contentType:false,

        data:formData,

        dataType:'json',

        success:function(res){

            if(res.status == 200){

                toastr.success(res.message);

                setTimeout(function(){
                    window.location.reload();
                },3000);

            }else{

                $.each(res.errors,function(field_name,error){
                    toastr.error(error);
                });

            }

        },

        error:function(){

            toastr.error('Something went wrong');

        }

    });

});
</script>

    @endsection