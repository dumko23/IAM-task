getUserData();


$(".massCheck").on("change", function () {
    if ($(this).prop("checked") === true && $(".single-check").length > 0) {
        $(".ok-button").prop("disabled", false);
    } else {
        $(".ok-button").prop("disabled", true);
    }
}).click(function () {
    $(".single-check").prop("checked", this.checked);
});

$(".single-check").on("change", function () {
    let allChecked = $(".single-check:not(:checked)").length === 0;
    $(".massCheck").prop("checked", allChecked);

    if ($(".single-check:checked").length > 0) {
        $(".ok-button").prop("disabled", false);
    } else {
        $(".ok-button").prop("disabled", true);
    }
});

$(".select-action").on("change", function () {
    let selected = $(this).val();
    if ($(this).val() !== null) {
        $(".ok-button").prop("disabled", false);
    }
    $(".select-action").each(function () {
        $(this).val(selected);
    })
})


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

$(".delete-btn").click(function () {
    setConfirm(
        "Delete user",
        `DELETE user '${$(this).closest("tr").find(".user-name").text()}'`,
        true
    );
})

$(".ok-button").click(function () {
    let prepareUsersId = [];
    let action = $(".select-action").val();
    $(".single-check:checkbox:checked").each(function () {
        prepareUsersId.push($(this).attr("id"));
    });

    if (prepareUsersId.length === 0) {
        setConfirm("Notice", "Please, select users. It looks like you've selected an action, but forgot to pick users...", false);
        return;
    }
    let users = "user(s) ";
    if ($(".massCheck").prop("checked") === true) {
        users = "all users";
    } else {
        prepareUsersId.forEach(user => {
            users += `'${$(`#${user}`).closest("tr").find(".user-name").text()}', `;
        });
        users = users.replace(/,\s*$/, "");
        if (prepareUsersId.length > 5) {
            users = `${prepareUsersId.length} users`;
        }
    }

    switch (action) {
        case "setActive":
            setConfirm("Confirm action - Set Active", `Are you sure you want to SET ACTIVE to ${users}?`, true);
            break;
        case "setInactive":
            setConfirm("Confirm action - Set Inactive", `Are you sure you want to SET INACTIVE to ${users}?`, true);
            break;
        case "delete":
            setConfirm("Confirm action - Delete", `Are you sure you want to DELETE ${users}?`, true);
            break;
        default:
            setConfirm("Notice", "Please, select action to perform. It looks like you've picked users, but forgot to select an action...", false);
    }
})

function setConfirm(actionName, actionText, flag) {
    $("#confirm-title").text(actionName);
    $(".confirm-text").text(actionText);
    if (flag) {
        $(".confirm-close").addClass("visible").removeClass("invisible");
        $(".confirm-save").addClass("visible").removeClass("invisible");
    } else {
        $(".confirm-close").addClass("visible").removeClass("invisible");
        $(".confirm-save").addClass("invisible").removeClass("visible");
    }
}

$(".add-btn").on("click", function () {
    console.log("add");
    assignUserData("", "", true, "");
})

$(".edit-btn").on("click", function () {
    console.log("edit");
    assignUserData("", "", false, "");
})

function assignUserData(name_first, name_last, status, role) {
    $("#name_first").val(name_first);
    $("#name_last").val(name_last);
    $("#statusSwitch").prop("checked", status);
    if (status === false) {
        $(".edit-status-mark").addClass("badge-secondary").removeClass("badge-success");
        $(".span-status").text("Inactive");
    } else {
        $(".edit-status-mark").addClass("badge-success").removeClass("badge-secondary");
        $(".span-status").text("Active");
    }
    $("#role").val(role);
}


let request = {
    action: '',  // delete/update/add/status
    id: [],      // array of ids
    data: null,  // object of user's data
}

let user = {
    "name_first": "",
    "name_last": "",
    "status": "false",
    "role": ""
}

function getUserData() {
    $.get('getUserList', function (data) {
        userData = JSON.parse(data);
        prepareUserList(userData.userData.user_data);
    })
}


function prepareUserList(userList){
    $(".loading-h").remove();
        if(userList.length > 0){
            $("tbody tr").remove();
            userData.userData.user_data.forEach(function (user) {
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
                )
            });
        } else {
            $(".btn-div").append(`<h5 class="text-center py-3 no-data">There is no data in DB</h5>`);
            $(".no-data").after(`<button type="button" class="btn btn-light border border-secondary refresh px-5">Refresh</button>`);
        }
}

$(".btn-div").on("click", ".refresh", function(){
    console.log('refresh');
    $(".no-data").remove();
    $(".refresh").remove();
    $(".btn-div").append(`<h5 class="text-center py-3 loading-h">Fetching data...</h5>`);
    getUserData();
})

console.log('works');