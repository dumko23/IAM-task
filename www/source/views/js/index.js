$('#massCheck').click(function(){
    let flag = $('#massCheck').prop('checked');
    let array = $('tbody').find('input').toArray();
    array.forEach(elem => {
        elem.checked = flag;
    })
});



console.log('works');