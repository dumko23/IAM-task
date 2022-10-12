$(".massCheck").click(function(){
    $(".single-check").prop("checked", this.checked);

});

$(".single-check").on("change", function() {
    allChecked = $('.single-check:not(:checked)').length === 0;
    $(".massCheck").prop("checked", allChecked);
});



console.log('works');