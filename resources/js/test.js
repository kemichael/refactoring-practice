
$(function(){
    loadSort();
})
//tablesorter読み込み用
function loadSort(){
    $('#pr-table').tablesorter();
}

$(function(){
    console.log('読み込みOK');
    deleteEvent();
    //検索ボタン押下イベントです
    $('#search-btn').on('click', function(e){
        console.log('検索押した');
        e.preventDefault();

        let formData = $('#search-form').serialize();

        $.ajax({
            url:'lists',
            type:'GET',
            data: formData,
            dataType: 'html'
        }).done(function(data){
            console.log('成功');
            let newTable = $(data).find('#products-table');
            $('#products-table').replaceWith(newTable);
            loadSort();
            deleteEvent();
        }).fail(function(){
            alert('通信失敗');
        })
    })

    
})

function deleteEvent(){
    //削除ボタン押下イベント
    $('.delete-btn').on('click', function(e){
        e.preventDefault();
        let deleteConfirm = confirm('削除しますか？');

        if(deleteConfirm == true){
            let clickEle = $(this);
            let deleteId = clickEle.data('delete-id');
            console.log(deleteId);

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url:'products/' + deleteId,
                type:'POST',
                data:{
                    '_method':'DELETE'
                }
            }).done(function(){
                console.log('削除成功');
                clickEle.parents('tr').remove();
                //テーブルの更新
                $('#pr-table').trigger("update");
            }).fail(function(){
                console.log('削除失敗');
            })
        }else{
            e.preventDefault();
        }
        
    })
}