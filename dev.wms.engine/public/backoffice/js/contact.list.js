$(document).ready(function () {
    var user_data_table = $('#id-datatable-contact').DataTable(
        {
            "aaSorting": [],
            "bProcessing": true,
            "bFilter": true,
            "bServerSide": true,
            "iDisplayLength": 20,
            "ajax": {
                url: global_utils.urls.ajax_list_contact,
                data: function (data) {
                    if (data.order[0])
                        data.order_by = data.columns[data.order[0].column].name + ' ' + data.order[0].dir;
                }
            },
            "columnDefs": [
                {name: "ct.ctName", targets: 0},
                {name: "ct.ctEmail", targets: 1},
                {name: "ct.ctMessage", targets: 2},
                {name: "ct.ctDate", targets: 3},
                {
                    name: "ct.ctIsAnswered",
                    render: function(data) {
                        return data == true ? global_utils.texts.yes : global_utils.texts.no
                    },
                    targets:4
                },
                {
                    name: "usr.id",
                    render: function (data, type, row) {
                        var href_delete = href_delete_default.replace('0', data);
                        var href_reply = href_reply_default.replace('0', data);
                        var reply_title = row[4] == true ? "Répondu" : "Répondre";
                        var reply_class = row[4] == true ? "btn-warning" : "btn-success";
                        if(row[4] == true) href_reply = 'javascript:void(0)';

                        var action = '<td><a class="btn " title="'+reply_title+'" ' +
                            '   href="' + href_reply + '">' +
                            '   <i class="fa fa-paper-plane"></i>' +
                            '   </a>' +
                            '<a class="btn btn-danger delete-item kl-remove-item" title="Supprimer" ' +
                            '   href="' + href_delete + '">' +
                            '   <i class="fa fa-trash"></i>' +
                            '   </a></td>';

                        return action;
                    },
                    orderable: false,
                    targets: 5
                }
            ],
            "drawCallback": function (settings) {
                if (settings.aiDisplay.length == 0)
                    $(".paginate_button.next").addClass('disabled');
                $('[data-toggle="tooltip"]').tooltip();
            }
        }
    );
});