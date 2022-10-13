<div class="d-flex justify-content-around">
    <button type="button"
            class="btn btn-light border border-secondary"
            data-toggle="modal"
            data-target="#modal">Add+
    </button>
    <div class="input-group w-50">
        <div class="input-group-prepend">
            <label class="input-group-text" >Apply</label>
        </div>
        <select class="custom-select select-action" >
            <option selected disabled value="select">Please select...</option>
            <option value="setActive">Set active</option>
            <option value="setInactive">Set not active</option>
            <option value="delete">Delete</option>
        </select>
    </div>
    <button type="button"
            class="btn btn-light border border-secondary ok-button"
            data-toggle="modal"
            data-target="#confirm"
            disabled>Ok</button>
</div>

