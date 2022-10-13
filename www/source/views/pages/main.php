<?php
$title = "User Management";


include('source/views/layouts/header.php');

?>
    <main class="container-fluid py-5 h-100">
        <div class="card container">
            <div class="card-body">
                <h5 class="card-title">Users</h5>

                <?php include('source/views/layouts/toolbar.php') ?>


                <table class="table table-bordered mt-3">
                    <thead>
                    <tr class="text-center">
                        <th scope="col">
                            <input type="checkbox" class="massCheck" aria-label="Select all users">
                        </th>
                        <th scope="col">Name</th>
                        <th scope="col">Role</th>
                        <th scope="col">Status</th>
                        <th scope="col">Actions</th>
                    </tr>
                    </thead>

                    <tbody>


                    </tbody>
                </table>
                <div class="container mb-3">
                    <div class="row">
                        <div class="col text-center btn-div">
                            <h5 class="text-center py-3 loading-h">Fetching data...</h5>
                        </div>
                    </div>
                </div>


                <?php include('source/views/layouts/toolbar.php') ?>
            </div>
        </div>


        <!-- Modal Form-->
        <div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
             aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Modal title</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form>
                            <div class="form-group">
                                <label for="name_first">First Name:</label>
                                <input type="text" class="form-control" id="name_first"
                                       placeholder="First Name" name="name_first">
                            </div>
                            <div class="form-group">
                                <label for="name_last">Last Name</label>
                                <input type="text" class="form-control" id="name_last"
                                       placeholder="Last Name" name="name_last">
                            </div>
                            <div class="custom-control custom-switch form-group">
                                <input type="checkbox" class="custom-control-input" id="statusSwitch">
                                <label class="custom-control-label" for="statusSwitch">Status:</label>
                                <span class="badge badge-pill badge-secondary p-2 text-center edit-status-mark align-middle"> </span>
                                <span class="span-status">Inactive</span>
                            </div>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <label class="input-group-text" for="inputGroupSelect01">Role</label>
                                </div>
                                <select class="custom-select" id="inputGroupSelect01">
                                    <option selected disabled>Please select...</option>
                                    <option value="1">Admin</option>
                                    <option value="2">User</option>
                                </select>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary invisible" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary">Save changes</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Confirm-->
        <div class="modal fade" id="confirm" tabindex="-1" role="dialog" aria-labelledby="confirm" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="confirm-title"></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <span class="confirm-text"></span>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary confirm-close invisible" data-dismiss="modal">
                            Cancel
                        </button>
                        <button type="button" class="btn btn-primary confirm-save">Confirm</button>
                    </div>
                </div>
            </div>
        </div>

    </main>
<?php
include('source/views/layouts/footer.php');
?>