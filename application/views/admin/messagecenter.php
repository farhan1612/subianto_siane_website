<?php 
    $this->load->view('admin/templates/header_admin');
    $this->load->view('admin/templates/sidebar_admin');
?>
	<!--main content start-->
	<section id="main-content">
	  <section class="wrapper site-min-height">
	      <!-- page start-->
	      <div class="row">
	          <div class="col-lg-12">
	              <section class="panel">
	                  <header class="panel-heading">
	                      Message Center
	                  </header>
	                  <div class="panel-body">
	                  <p><?=$this->session->flashdata('message')?> </p>
	                        <div class="adv-table">
	                            <table  class="display table table-bordered table-striped" id="dataproject">
	                              <thead>
	                              <tr>
	                                  <th>No</th>
                                      <th>Nama</th>
                                      <th>Email</th>
                                      <th>Message</th>
                                      <th>Date</th>
                                      <th>Status</th>
                                      <th>Action</th> 
	                              </tr>
	                              </thead>
	                              <tbody>
	                            <?php
	                            if(isset($loadallmessage)){
	                            	$no=1;
	                            	foreach ($loadallmessage as $lam) {
	                            		//$prostory = htmlentities();
	                            		//echo htmlentities($lap->project_story);
	                            ?>		
		                              <tr class="">
		                                  <td><?php echo $no;?></td>
		                                  <td><?php echo $lam->name;?></a> </td>
		                                  <td><?php echo $lam->email;?></td>
		                                  <td><?php echo $lam->message;?></td>
		                                  <td><?php echo $lam->date;?></td>
		                                  <td><?php echo $lam->status;?></td>
                                          <td>
                                            <button class="btn btn-warning" data-toggle="modal" data-target="#viewModal" data-name="<?php echo $lam->name;?>" data-id="<?php echo $lam->id;?>" data-email="<?php echo $lam->email;?>" data-message="<?php echo $lam->message;?>" data-date="<?php echo $lam->date;?>">
                                                <i class="fa fa-search"></i>
                                            </button>
		                                  </td>
		                              </tr>
	                            <?php
	                            		$no++;	
	                            	}
	                            }else{
	                            	// echo nothing
	                            	echo '';
	                            }
	                            ?>
	                              	</tbody>
	                              <tfoot>
	                              <tr>
	                                  <th>No</th>
                                      <th>Nama</th>
                                      <th>Email</th>
                                      <th>Message</th>
                                      <th>Date</th>
                                      <th>Status</th>
                                      <th>Action</th>
	                              </tr>
	                              </tfoot>
	                  			</table>
	                        </div>
	                  </div>
	              </section>
					<!-- MODAL FOR VIEW -->
					<div class="modal fade" id="viewModal" tabindex="-1" role="dialog" aria-labelledby="viewModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                    <h4 class="modal-name" id="viewModalLabel">View Message...</h4>
                                </div>
                                <div class="modal-body">
                                    <table>
                                    	<tr class="tr-name">
                                    		<td><b>Name</td><td>:</td>
                                    		<td class="td-name"></td>
                                    	</tr>
                                    	<tr class="tr-email">
                                    		<td><b>Email</td><td>:</td>
                                    		<td class="td-email"></td>
                                    	</tr>
                                    	<tr class="tr-message">
                                    		<td><b>Message</td><td>:</td>
                                    		<td class="td-message"></td>
                                    	</tr>
                                    	<tr class="tr-date">
                                    		<td><b>Date</td><td>:</td>
                                    		<td class="td-date"></td>
                                    	</tr>
                                    </table>
                                </div>
                                <div class="modal-footer">
                                    <a id="viewlink" href="" class="btn btn-danger">Read</a>
                                </div>
                            </div>
                        </div>
                    </div>
					<!-- END MODAL FOR VIEW -->
	              	<!-- MODAL FOR DELETE -->
                    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                    <h4 class="modal-title" id="deleteModalLabel">Delete...</h4>
                                </div>
                                <div class="modal-body">
                                    <h2 id="h2alert"></h2>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                                    <a id="deletelink" href="" class="btn btn-danger">Delete</a>
                                </div>
                            </div>
                        </div>
                    </div>  
                    <!-- END MODAL FOR DELETE -->
	          </div>
	      </div>
	      <!-- page end-->
	  </section>
	</section>
	<!--main content end-->

    <script src="/assets/admin/js/jquery.js"></script>
    <script type="text/javascript">
        $(document).ready(function(){
        	/*
        	 * modal for view project
        	 */
        	$('#viewModal').on('show.bs.modal', function(event){
        		var button = $(event.relatedTarget);
        		var name = button.data('name');
        		var email = button.data('email');
        		var message = button.data('message');
        		var date = button.data('date');
                var id = button.data('id')
        		var modal = $(this);
        		modal.find('.modal-name').text(name);
        		modal.find('.modal-body table tbody tr td.td-name').text(name);
        		modal.find('.modal-body table tbody tr td.td-email').text(email);
        		modal.find('.modal-body table tbody tr td.td-message').text(message);
        		modal.find('.modal-body table tbody tr td.td-date').text(date);
                modal.find('.modal-footer a#viewlink').attr("href", 'messageupdateread/'+id)
        		
        	});
	       	/*
	         * modal for delete project
	         */
	        $('#deleteModal').on('show.bs.modal', function (event) {
	            var button = $(event.relatedTarget)// Button that triggered the modal
	            var title = button.data('title')
	            var id = button.data('id')
	            var modal = $(this)
	            modal.find('.modal-title').text(title)
	            modal.find('.modal-body h2#h2alert').text('Hapus Project ' +title+'?')
	            modal.find('.modal-footer a#deletelink').attr("href", 'projectdelete/'+id)
	        });
        })
    </script>

<?php
    $this->load->view('admin/templates/footer_admin');
?>