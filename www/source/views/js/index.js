$(".massCheck").click(function () {
    $(".single-check").prop("checked", this.checked);

});

$(".single-check").on("change", function () {
    allChecked = $(".single-check:not(:checked)").length === 0;
    $(".massCheck").prop("checked", allChecked);
});

$("#statusSwitch").on("change", function () {
    let elem = $(".edit-status-mark");
    let switcher = $("#statusSwitch");
    if (switcher.prop("checked") === true) {
        elem.addClass("badge-success").removeClass("badge-secondary")
        $(".span-status").text("Active");
    } else if (switcher.prop("checked") === false) {
        elem.addClass("badge-secondary").removeClass("badge-success");
        $(".span-status").text("Inactive");
    }
});

$(".delete-btn").click(function () {
    setConfirm(
        "Delete user",
        `DELETE user '${$(this).closest("tr").find(".user-name").text()}'`
    );
})

$(".ok-button").click(function () {
    let prepareUsersId = [];
    let action = $(".custom-select").val();
    $(".single-check:checkbox:checked").each(function () {
        prepareUsersId.push($(this).attr("id"));
    });

    if (prepareUsersId.length === 0) {
        setConfirm("Notice", "Please, select users. It looks like you selected action, but forgot to pick users...", false);
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
            setConfirm("Notice", "Please, select action to perform. It looks like you picked users, but forgot to select action...", false);
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

$(".custom-select").on("change", function (){
    if ($(".custom-select").val() === null){
        $(".ok-button").prop("disabled", true);
        console.log($(".custom-select").val())
    } else {
        console.log($(".custom-select").val())

        $(".ok-button").prop("disabled", false);
    }
})




request = {
    action: '',  // delete/update/add/status
    id: [],      // array of ids
    data: null,  // object of user's data
}

console.log('works');