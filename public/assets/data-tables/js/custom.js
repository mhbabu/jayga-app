/***********************
 SWEET ALERT START HERE
 ***********************/

 $(document.body).on("click", ".action-delete", function (ev) {
    ev.preventDefault();
    let url = $(this).attr("href");
    let table = $(this).attr("table");
    warnBeforeAction(url, table);
});

function warnBeforeAction(URL, table) {
    console.log(URL);
    swal(
        {
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            type: "warning",
            showCancelButton: true,
            cancelButtonText: "No, cancel!",
            cancelButtonClass: "btn-danger",
            confirmButtonClass: "btn-primary",
            confirmButtonText: "Yes, proceed!",
            closeOnConfirm: false,
        },
        function () {
            var _token = $('meta[name="csrf-token"]').attr("content");
            $.ajax({
                method: "DELETE",
                url: URL,
                dataType: "json",
                data: { _token: _token },
                success: function (response) {
                    swal(
                        "Deleted!",
                        "Your imaginary file has been deleted.",
                        "success"
                    );
                    //    swal.close();
                    $(`#${table}`).DataTable().ajax.reload(null, false);
                },
            });
        }
    );
}

// /**************************
//  DYNAMIC MODAL SCRIPT HERE
//  **************************/
// $(document.body).on("click", ".AppModal", function (e) {
//     e.preventDefault();
//     $("#ModalContent").html(
//         '<div style="text-align:center;"><h3 class="text-primary">Loading Form...</h3></div>'
//     );
//     $("#ModalContent").load(
//         $(this).attr("href"),
//         function (response, status, xhr) {
//             if (status === "error") {
//                 alert("error");
//                 $("#ModalContent").html(
//                     "<p>Sorry, but there was an error:" +
//                         xhr.status +
//                         " " +
//                         xhr.statusText +
//                         "</p>"
//                 );
//             }
//             return this;
//         }
//     );
// });

// /********************
//  VALIDATION START HERE
//  ********************/
// $("#dataForm").validate({
//     errorPlacement: function () {
//         return false;
//     },
// });
