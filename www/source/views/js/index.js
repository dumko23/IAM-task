getUserData();


// request object markup
let request = {
    action: '',  // delete/update/add/status/drop
    id: [],      // array of ids
    data: [],    // object of user's data
    status: []   // status to change for
}

// object of user's data
let user = {
    "name_first": "",
    "name_last": "",
    "status": "false",
    "role": ""
}

// List of fetched users
let fetchedUserList = {};


// mass-check functionality
$(".massCheck").on("change", function () {
    if ($(this).prop("checked") === true && $(".single-check").length > 0) {
        $(".ok-button").prop("disabled", false);
    } else if ($(this).prop("checked") === false && $(".select-action").val() !== null) {
        $(".ok-button").prop("disabled", false);
    } else {
        $(".ok-button").prop("disabled", true);
    }
}).click(function () {
    $(".single-check").prop("checked", this.checked);
});


// single-to-mass-check functionality
$("tbody").on("change", ".single-check", function () {
    let allChecked = $(".single-check:not(:checked)").length === 0;
    $(".massCheck").prop("checked", allChecked);

    if ($(".single-check:checked").length > 0) {
        $(".ok-button").prop("disabled", false);
    } else if ($(".single-check:checked").length === 0 && $(".select-action").val() !== null) {
        $(".ok-button").prop("disabled", false);
    } else {
        $(".ok-button").prop("disabled", true);
    }
});


// action select functionality
$(".select-action").on("change", function () {
    let selected = $(this).val();
    if ($(this).val() !== null && $(".single-check").length > 0) {
        $(".ok-button").prop("disabled", false);
    }
    $(".select-action").each(function () {
        $(this).val(selected);
    })
})


// status switch in modals functionality
$("#statusSwitch").on("change", function () {
    let elem = $(".edit-status-mark");
    if ($(this).prop("checked") === true) {
        elem.addClass("badge-success").removeClass("badge-secondary")
        $(".span-status").text("Active");
    } else if ($(this).prop("checked") === false) {
        elem.addClass("badge-secondary").removeClass("badge-success");
        $(".span-status").text("Inactive");
    }
});


// mass-action assign functionality
$(".ok-button").click(function () {
    let prepareUsersId = [];
    let action = $(".select-action").val();
    $(".single-check:checkbox:checked").each(function () {
        prepareUsersId.push($(this).attr("id"));
    });

    if (action === null) {
        setConfirm(
            "Notice",
            "Please, select action to perform. It looks like you've picked users, but forgot to select an action...",
            false,
            'notice');
        return;
    }

    if (prepareUsersId.length === 0) {
        setConfirm(
            "Notice",
            "Please, select users. It looks like you've selected an action, but forgot to pick users...",
            false,
            'notice');
        return;
    }
    let users = "user(s) ";
    let id = [];
    if ($(".massCheck").prop("checked") === true && action === "delete") {
        users = "all users";
        action = "drop";
    } else {
        prepareUsersId.forEach(user => {
            users += `'${$(`.user-name${fetchedUserList[user].id}`).text()}', `;
            id.push(fetchedUserList[user].id)
            request.id = id;
        });
        users = users.replace(/,\s*$/, "");

        if (prepareUsersId.length > 5) {
            users = `${prepareUsersId.length} users`;
        }
    }

    switch (action) {
        case "setActive":
            setConfirm(
                "Confirm action - Set Active",
                `Are you sure you want to SET ACTIVE to ${users}?`,
                true,
                'confirm');
            request.action = 'setActive';
            request.status = 'true';
            break;
        case "setInactive":
            setConfirm(
                "Confirm action - Set Inactive",
                `Are you sure you want to SET INACTIVE to ${users}?`,
                true,
                'confirm');
            request.action = 'setInactive';
            request.status = 'false';
            break;
        case "delete":
            setConfirm(
                "Confirm action - Delete",
                `Are you sure you want to DELETE ${users}?`,
                true,
                'confirm');
            request.action = "delete";
            break;
        case "drop":
            setConfirm(
                "Confirm action - Delete",
                `Are you sure you want to DELETE ${users}?`,
                true,
                'confirm');
            request.action = "drop";
            break;
    }
})


// setting popup's data
function setConfirm(actionName, actionText, flag, type, error = '') {
    $("#confirm-title").text(actionName);
    $(".confirm-text").text(actionText);
    if (flag) {
        $(".confirm-save").prop("disabled", false);
    } else {
        $(".confirm-save").prop("disabled", true);
    }

    if (type === 'notice') {
        $('.confirm-notice').empty().append(
            `<button type="button" class="btn btn-secondary confirm-close" data-dismiss="modal">
                            Close
                        </button>`
        );
    } else {
        $('.confirm-notice').empty().append(
            `<button type="button" class="btn btn-secondary confirm-close" data-dismiss="modal">
                            Cancel
                        </button>
                        <button type="button" class="btn btn-primary confirm-save">Confirm</button>`
        );
    }

    if (error === '') {
        $(".error-code").text(``);
        $(".error-message").text(``);
        $(".error-where").text(``);
        $(".error-line").text(``);
    } else {
        $(".error-code").text(`Error code: "${error.code}"`);
        $(".error-message").text(`Error Message: "${error.message}"`);
        $(".error-where").text(`Where: "${error.gotIn}"`);
        $(".error-line").text(`Line: "${error.line}"`);

        // $(".btn-div").empty();
        // $(".btn-div").append(`<h5 class="text-center py-3 no-data">There is no data in DB</h5>`);
        // $(".no-data").after(`<button type="button" class="btn btn-light border border-secondary refresh px-5">Refresh</button>`);
        $('#confirm').modal('show');
    }
}


// cleaning data on modal close
$(".close-btn").on("click", function () {
    dropRequestAndUserData();
});


// triggering add-user action
$(".add-btn").on("click", function () {
    assignUserDataToModal("", "", "true", "Add new user");
    request.action = "add";
})


// triggering edit-user action with data assigning
$("table").on("click", ".edit-btn", function () {
    let id = $(this).closest("tr").find("input").attr("id");
    assignUserDataToModal(
        fetchedUserList[id].name_first,
        fetchedUserList[id].name_last,
        fetchedUserList[id].status,
        `Edit user: ${fetchedUserList[id].name_first} ${fetchedUserList[id].name_last}`,
        fetchedUserList[id].role
    );
    request.id = [fetchedUserList[id].id];
    request.action = 'update';
})


// triggering delete-user action
$("tbody").on("click", ".delete-btn", function () {

    let id = fetchedUserList[$(this).closest("tr").find("input").attr("id")].id;
    setConfirm(
        "Delete user",
        `DELETE user '${$(this).closest("tr").find(".user-name" + id).text()}'?`,
        true,
        'confirm'
    );
    request.id[0] = fetchedUserList[`user${id}`].id;
    request.action = "delete";
})


// assigning user's data to modal
function assignUserDataToModal(name_first, name_last, status, title, role = null) {
    $("#title").text(title);
    $("#name_first").val(name_first);
    $("#name_last").val(name_last);
    if (status === 'false') {
        $(".edit-status-mark").addClass("badge-secondary").removeClass("badge-success");
        $(".span-status").text("Inactive");
        $("#statusSwitch").prop("checked", false);
    } else {
        $(".edit-status-mark").addClass("badge-success").removeClass("badge-secondary");
        $(".span-status").text("Active");
        $("#statusSwitch").prop("checked", true);
    }
    if (role !== null) {
        $("#role").val(role);
    }
}


// Get all users methods
function getUserData() {
    $("tbody").empty();
    $(".btn-div").empty();
    $(".btn-div").append(`<h5 class="text-center py-3 loading-h">Fetching data...</h5>`);

    $.get('getUserList', function (data) {
        let userData = JSON.parse(data);
        if (userData.error !== null) {

            setConfirm(
                'Backend responded with error',
                '',
                false,
                'notice',
                userData.error);
        } else {
            prepareUserList(userData.user_data);
        }
    })
}


// rendering user table with fetched data
function prepareUserList(userList) {
    $(".loading-h").remove();
    $("tbody").empty();
    if (userList.length > 0) {
        // $("tbody tr").remove();

        userList.forEach(function (user) {
            appendTableRow(user);
        });
    } else {
        $(".btn-div").append(`<h5 class="text-center py-3 no-data">There is no data in DB</h5>`);
        $(".no-data").after(`<button type="button" class="btn btn-light border border-secondary refresh px-5">Refresh</button>`);
    }
}


// re-fetch data from DB if there was no any
$(".btn-div").on("click", ".refresh", function () {
    $(".no-data").remove();
    $(".refresh").remove();
    getUserData();
});


// delete 1 user methods
function deleteUser(request) {
    $.post("delete", {'request': request}, function (data) {
        let response = JSON.parse(data);
        if (response.error !== null) {
            setConfirm(
                'Backend responded with error',
                '',
                false,
                'notice',
                response.error);
        } else {
            let userIds = response['id'];
            userIds.forEach(function (id) {
                $(`#user${id}`).closest('tr').remove();
                delete fetchedUserList[`user${id}`];
            })
        }
        dropRequestAndUserData();
    });
}


// setting action trigger to popup 'confirm' button
$(document).on('click', '.confirm-save', function () {
    $('#confirm').modal('hide');

    if (request.action === 'delete') {
        deleteUser(request);
    } else if (request.action === 'drop') {
        dropUsers();
    } else if (request.action === 'setActive' || request.action === 'setInactive') {
        updateStatus(request);
    }
})


// create new user methods
function saveUser(request) {
    $.post("saveUser", {'request': request}, function (data) {
        let response = JSON.parse(data)
        if (response.error !== null) {
            if (backendValidation(response.error)) {
                return;
            } else {
                setConfirm(
                    'Backend responded with error',
                    '',
                    false,
                    'notice',
                    response.error);
            }
        }
        $('#modal').modal('hide');
        appendTableRow(response['user_data']);
        dropRequestAndUserData();
    });
}


function appendTableRow(data) {

    $("tbody").append(
        `<tr class="text-center">
                        <td class="text-center align-middle">
                            <input id="user${data.id}" class="single-check" type="checkbox"
                                   aria-label="Select this user">
                        </td>
                        <td class=" align-middle user-name${data.id}">${data.name_first} ${data.name_last}</td>
                        <td class=" align-middle user-role${data.id}">${data.role}</td>
                        <td class=" align-middle">
                                <span class="badge badge-pill status${data.id} ${data.status === 'true' ? 'badge-success' : 'badge-secondary'} p-2 text-center"> </span>
                        </td>
                        <td class="text-center">
                            <div class="btn-group" role="group" aria-label="Basic example">
                                <button type="button"
                                        class="btn btn-light border border-secondary edit-btn"
                                        data-toggle="modal"
                                        data-target="#modal">
                                    <i class="fa-solid fa-user-pen"></i>
                                </button>
                                <button type="button"
                                        class="btn btn-light border border-secondary delete-btn "
                                        data-toggle="modal"
                                        data-target="#confirm">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>`
    );
    fetchedUserList[`user${data.id}`] = data;
}


// creating user object, validating and performing add/update action
$(document).on('click', '#save-user', function () {
    formUser(
        $("#name_first").val().trim(),
        $("#name_last").val().trim(),
        $("#statusSwitch").prop("checked"),
        $("#role").val()
    )
    // let usedId = ''
    if (validation(user) === true) {
        request.data[0] = user;

        if (request.action === 'add') {
            saveUser(request);
        } else if (request.action === 'update') {
            updateUser(request);
        }
    }
})


// delete all users
function dropUsers() {
    $.post("drop", function (data) {
        let response = JSON.parse(data)
        if (response.error !== null) {
            setConfirm(
                'Backend responded with error',
                '',
                false,
                'notice',
                response.error);
        }
        $('tbody').empty();
        $(".btn-div").append(`<h5 class="text-center py-3 no-data">There is no data in DB</h5>`);
        $(".no-data").after(`<button type="button" class="btn btn-light border border-secondary refresh px-5">Refresh</button>`);
        fetchedUserList = {};
        dropRequestAndUserData();
    });
}


// update user status
function updateStatus() {
    $.post("updateStatus", {'request': request}, function (data) {
        let response = JSON.parse(data)
        if (response.error !== null) {
            setConfirm(
                'Backend responded with error',
                '',
                false,
                'notice',
                response.error);
        } else {
            let userIds = response['id'];
            userIds.forEach(function (id) {
                $(`.status${id}`).addClass(`${response['user_status'] === 'true' ? 'badge-success' : 'badge-secondary'}`)
                    .removeClass(`${response['user_status'] === 'true' ? 'badge-secondary' : 'badge-success'}`)
                fetchedUserList[`user${id}`]['status'] = response['user_status'];
            })
        }
        dropRequestAndUserData();
    });
}


// update user info
function updateUser(request) {
    $.post("updateUser", {'request': request}, function (data) {
        let response = JSON.parse(data)
        if (response.error !== null) {
            if (backendValidation(response.error)) {
                return;
            } else {
                setConfirm(
                    'Backend responded with error',
                    '',
                    false,
                    'notice',
                    response.error);
            }
        }
        $('#modal').modal('hide');
        let updatedUser = response['user_data'];
        fetchedUserList[`user${updatedUser.id}`] = updatedUser;
        $(`.user-name${updatedUser.id}`).text(`${updatedUser.name_first} ${updatedUser.name_last}`);
        $(`.status${updatedUser.id}`)
            .addClass(`${updatedUser.status === 'true' ? 'badge-success' : 'badge-secondary'}`)
            .removeClass(`${updatedUser.status === 'true' ? 'badge-secondary' : 'badge-success'}`)
        $(`.user-role${updatedUser.id}`).val(updatedUser.role);
        dropRequestAndUserData();
    });
}


// form new user object
function formUser(name_first, name_last, status, role) {
    user.name_first = name_first;
    user.name_last = name_last;
    user.status = status;
    user.role = role;
}


// validate input
function validation(user) {
    let noErrors = true;
    if (user.name_first === '') {
        $("#name-first-error").text('Input is empty');
        noErrors = false;
    } else if (user.name_first.length > 35) {
        $("#name-first-error").text('Input is longer than 35 symbols');
        noErrors = false;
    } else {
        $("#name-first-error").text('');
    }
    if (user.name_last === '') {
        $("#name-last-error").text('Input is empty');
        noErrors = false;
    } else if (user.name_last.length > 35) {
        $("#name-last-error").text('Input is longer than 35 symbols');
        noErrors = false;
    } else {
        $("#name-last-error").text('');
    }
    if (user.role === null) {
        $("#role-error").text("Select user's role");
        noErrors = false;
    } else {
        $("#role-error").text('');
    }
    return noErrors;
}


function backendValidation(error) {
    let validationError = false;
    if (error.name_first !== null) {
        $("#name-first-error").text(error.name_first);
        validationError = true;
    } else {
        $("#name-first-error").text('');
    }
    if (error.name_last !== null) {
        $("#name-last-error").text(error.name_last);
        validationError = true;
    } else {
        $("#name-last-error").text('');
    }
    if (error.role !== null) {
        $("#role-error").text(error.role);
        validationError = true;
    } else {
        $("#role-error").text('');
    }
    return validationError;
}


// Remove validation messages from modal on closing
$('#modal').on('hide.bs.modal', function (e) {
    $("#name-first-error").text('');
    $("#name-last-error").text('');
    $("#role-error").text('');
    $("#statusSwitch").prop("checked", true);
    $("#role").val('default');
})


// cleaning request object and dropping checkboxes and selects
function dropRequestAndUserData() {
    request.action = '';
    request.id = [];
    request.data = [];
    request.status = [];
    formUser('', '', 'false', '');
    $(".massCheck").prop("checked", false);
    $(".single-check").prop("checked", false);
    $(".select-action").val("select");
    $(".ok-button").prop("disabled", true);
}

