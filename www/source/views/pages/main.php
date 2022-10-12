<?php
$title = "User Management";


include('source/views/layouts/header.php');

?>
    <main class="container-fluid pt-5 h-100">
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
                    <?php
                    foreach ($data['user_data'] as $user):
                        ?>
                        <tr class="text-center">
                            <td class="text-center align-middle">
                                <input id="user<?php echo $user['id'] ?>" class="single-check" type="checkbox" aria-label="Select this user">
                            </td>
                            <td class=" align-middle"><?php echo $user['name_first'] . ' ' . $user['name_last'] ?></td>
                            <td class=" align-middle"><?php echo $user['role'] ?></td>
                            <td class=" align-middle">
                                <?php if ($user['status'] === 'true'): ?>
                                    <span class="badge badge-pill badge-success p-2 text-center"> </span>
                                <?php elseif ($user['status'] === 'false'): ?>
                                    <span class="badge badge-pill badge-secondary p-2 text-center"> </span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <div class="btn-group" role="group" aria-label="Basic example">
                                    <button type="button" class="btn btn-light border border-secondary">Left</button>
                                    <button type="button" class="btn btn-light border border-secondary">Middle</button>
                                </div>
                            </td>
                        </tr>
                    <?php
                    endforeach;
                    ?>
                </table>

                <?php include('source/views/layouts/toolbar.php') ?>
            </div>
        </div>

        <!-- switcher for the modal -->
        <div class="custom-control custom-switch">
            <input type="checkbox" class="custom-control-input" id="customSwitch1">
            <label class="custom-control-label" for="customSwitch1">Toggle this switch element</label>
        </div>


    </main>
<?php
include('source/views/layouts/footer.php');
?>