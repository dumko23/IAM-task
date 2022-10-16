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
        setConfirm("Notice", "Please, select action to perform. It looks like you've picked users, but forgot to select an action...", false);
        return;
    }

    if (prepareUsersId.length === 0) {
        setConfirm("Notice", "Please, select users. It looks like you've selected an action, but forgot to pick users...", false);
        return;
    }
    let users = "user(s) ";
    let id = [];
    if ($(".massCheck").prop("checked") === true && action === "delete") {
        users = "all users";
        action = "drop";
    } else {
        prepareUsersId.forEach(user => {
            users += `'${$(`#${user}`).closest("tr").find(".user-name").text()}', `;
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
            setConfirm("Confirm action - Set Active", `Are you sure you want to SET ACTIVE to ${users}?`, true);
            request.action = 'setActive';
            request.status = 'true';
            break;
        case "setInactive":
            setConfirm("Confirm action - Set Inactive", `Are you sure you want to SET INACTIVE to ${users}?`, true);
            request.action = 'setInactive';
            request.status = 'false';
            break;
        case "delete":
            setConfirm("Confirm action - Delete", `Are you sure you want to DELETE ${users}?`, true);
            request.action = "delete";
            break;
        case "drop":
            setConfirm("Confirm action - Delete", `Are you sure you want to DELETE ${users}?`, true);
            request.action = "drop";
            break;
    }
})


// setting popup's data
function setConfirm(actionName, actionText, flag, error = '') {
    $("#confirm-title").text(actionName);
    $(".confirm-text").text(actionText);
    if (flag) {
        $(".confirm-close").addClass("visible").removeClass("invisible");
        $(".confirm-save").addClass("visible").removeClass("invisible");
    } else {
        $(".confirm-close").addClass("visible").removeClass("invisible");
        $(".confirm-save").addClass("invisible").removeClass("visible");
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
        $(".btn-div").empty();
        $(".btn-div").append(`<h5 class="text-center py-3 no-data">There is no data in DB</h5>`);
        $(".no-data").after(`<button type="button" class="btn btn-light border border-secondary refresh px-5">Refresh</button>`);
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
    setConfirm(
        "Delete user",
        `DELETE user '${$(this).closest("tr").find(".user-name").text()}'?`,
        true
    );
    request.id[0] = fetchedUserList[$(this).closest("tr").find("input").attr("id")].id;
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

            setConfirm('Backend responded with error', '', false, userData.error);
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
            $("tbody").append(
                `<tr class="text-center">
                        <td class="text-center align-middle">
                            <input id="user${user.id}" class="single-check" type="checkbox"
                                   aria-label="Select this user">
                        </td>
                        <td class=" align-middle user-name">${user.name_first} ${user.name_last}</td>
                        <td class=" align-middle">${user.role}</td>
                        <td class=" align-middle">
                                <span class="badge badge-pill ${user.status === 'true' ? 'badge-success' : 'badge-secondary'} p-2 text-center"> </span>
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
                                        class="btn btn-light border border-secondary delete-btn"
                                        data-toggle="modal"
                                        data-target="#confirm">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>`
            );
            fetchedUserList[`user${user.id}`] = user;
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
            setConfirm('Backend responded with error', '', false, response.error);
        }
        getUserData();
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
            setConfirm('Backend responded with error', '', false, response.error);
        }
        getUserData();
        dropRequestAndUserData();
    });
}


// creating user object, validating and performing add/update action
$(document).on('click', '#save-user', function () {
    formUser(
        $("#name_first").val(),
        $("#name_last").val(),
        $("#statusSwitch").prop("checked"),
        $("#role").val()
    )
    if (validation(user) === true) {
        request.data[0] = user;

        $('#modal').modal('hide');

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
            setConfirm('Backend responded with error', '', false, response.error);
        }
        getUserData();
        dropRequestAndUserData();

    });
}


// update user status
function updateStatus() {
    $.post("updateStatus", {'request': request}, function (data) {
        let response = JSON.parse(data)
        if (response.error !== null) {
            setConfirm('Backend responded with error', '', false, response.error);
        }
        getUserData();
        dropRequestAndUserData();
    });
}


// update user info
function updateUser(request) {
    $.post("updateUser", {'request': request}, function (data) {
        let response = JSON.parse(data)
        if (response.error !== null) {
            setConfirm('Backend responded with error', '', false, response.error);
        }
        getUserData();
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


// Remove validation messages from modal on closing
$('#modal').on('hide.bs.modal', function (e) {
    $("#name-first-error").text('');
    $("#name-last-error").text('');
    $("#role-error").text('');
    $("#statusSwitch").prop("checked", true);
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

