// (function ($) {
//     $(document).on('click', '.approve-user', function (e) {
//         e.preventDefault();
//         var userId = $(this).data('user-id');
//         var data = {
//             action: 'approve_user',
//             user_id: userId
//         };
//         $.post(ajaxurl, data, function (response) {
//             if (response.success) {
//                 alert('User approved successfully.');
//                 // You can perform additional actions here, such as updating the UI.
//             } else {
//                 alert('Failed to approve user.');
//             }
//         });
//     });

//     $(document).on('click', '.reject-user', function (e) {
//         e.preventDefault();
//         var userId = $(this).data('user-id');
//         var data = {
//             action: 'reject_user',
//             user_id: userId
//         };
//         $.post(ajaxurl, data, function (response) {
//             if (response.success) {
//                 alert('User rejected successfully.');
//                 // You can perform additional actions here, such as updating the UI.
//             } else {
//                 alert('Failed to reject user.');
//             }
//         });
//     });
// })(jQuery);
