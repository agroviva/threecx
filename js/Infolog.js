class Infolog {

    static get SearchUrl() {
        return "/egroupware/threecx/graph/search.php";
    }

    static get InfologUrl() {
        return "/egroupware/threecx/graph/infolog.php";
    }

    constructor() {
        $(document).on("click", "#CreateInfolog .address", function(){
            Infolog.selectAddress(this);
        });

        this.InitDate();
        this.PopUp();
        this.InputAddressInfo();
        this.InputResponsible();
        this.SubmitForm();
        this.LoadStatuses();
    }

    SubmitForm() {
        $("#CreateInfolog button").click(function(e) {
            e.preventDefault();
            var data = $("#CreateInfolog form").serialize();
            $.ajax({
                type: "POST",
                url: Infolog.InfologUrl + "?action=newInfolog",
                data: data,
                // dataType: "json",
                success: function(data) {
                    if (data.response = "success") {
                        var call_id = $('input#CallIDInfo').val();
                        $(".call[data-id='"+call_id+"']").attr("data-marked", "true");
                        $('#CreateInfolog').modal('toggle');
                    }
                },
                error: function() {
                    alert('error handling here');
                }
            });
        });
    }

    InputAddressInfo() {
        $("#inputAddressInfo").bind("click", function() {
            if (this.value == "") {
                return "";
            }
            $.get(Infolog.SearchUrl + "?query=" + this.value + "&app=addressbook", function(data) {
                var html = "";
                for (var i = 0; i < data.length; i++) {
                    if (i == 10) {
                        break;
                    }
                    var address = data[i];
                    var hasId = false;
                    $("#CreateInfolog .linked_addresses .address.active").each(function(key, elem) {
                        if ($(this).attr('data-uid') == address["id"]) {
                            hasId = true;
                        }
                    });
                    if (hasId) {
                        hasId = false;
                        continue;
                    }
                    html += '<div class="address" data-uid="' + address["id"] + '"><p>' + (address["label"]["label"] || address["label"]) + '</p></div>'
                }
                $("#CreateInfolog .linked_addresses .address").not('.active').remove();
                $("#CreateInfolog .linked_addresses").append(html);
            });
        });
        $('#inputAddressInfo').keypress(function(e) {
            if (e.which == 13) {
                $('#inputAddressInfo').click();
                return false;
            }
        });
    }

    InputResponsible() {
        $('#inputResponsibleInfo').keyup(function(e) {
            if (e.which == 13) {
                if (this.value == "") {
                    return "";
                }
                $.get(Infolog.SearchUrl + "?query=" + this.value + "&app=addressbook&type=account&account_type=both", function(data) {
                    var html = "";
                    for (var i = 0; i < data.length; i++) {
                        if (i == 10) {
                            break;
                        }
                        var user = data[i];
                        var hasId = false;
                        $("#CreateInfolog .responsible_users .user.active").each(function(key, elem) {
                            if ($(this).attr('data-uid') == user["id"]) {
                                hasId = true;
                            }
                        });
                        if (hasId) {
                            hasId = false;
                            continue;
                        }
                        html += '<div class="user" onclick="Infolog.selectResponsible(this)" data-uid="' + user["id"] + '"><p>' + (user["label"]["label"] || user["label"]) + '</p></div>'
                    }
                    $("#CreateInfolog .responsible_users .user").not('.active').remove();
                    $("#CreateInfolog .responsible_users").append(html);
                });
                return false;
            }
            // $(".responsible_users .user").click(function(){
            // 	$(this).toggleClass("active");
            // });
        });
    }

    InitDate() {
        $('#inputStartDateInfo').datetimepicker({
            locale: 'de',
            icons: {
                time: "fa fa-clock-o",
                date: "fa fa-calendar",
                up: "fa fa-chevron-up",
                down: "fa fa-chevron-down",
                previous: 'fa fa-chevron-left',
                next: 'fa fa-chevron-right',
                today: 'fa fa-screenshot',
                clear: 'fa fa-trash',
                close: 'fa fa-remove'
            }
        });
    }

    PopUp() {
        $(".call_id, .destination").click(function(e) {
            e.preventDefault();
            var number = $(this).data("number");
            egw.open_link('https://agroviva.3cx.de:5001/webclient/#/call?phone=' + number, '_phonecall', '325x410')
        })
    }

    static Create(elem) {
        Infolog.RenderID(elem);
        var call_id = $(elem).parent().parent().find(".call_id");
        var destination = $(elem).parent().parent().find(".destination");

        var regExp = /\(([^)]+)\)/;
        var matches = regExp.exec(call_id.data("title"));
        //matches[1] contains the value between the parentheses
        var InfologTitle;
        if (matches && matches[1].length <= 3){
            InfologTitle = destination.data("title");
        } else {
            InfologTitle = call_id.data("title");
        }
        
        var number = call_id.data("number");
        $("#inputTitleInfo, #inputAddressInfo").val(InfologTitle);
        $("#inputNumberInfo").val(number);
        $("#CreateInfolog .responsible_users .user, #CreateInfolog .linked_addresses .address").remove();
        $("#inputResponsibleInfo, #descriptionInfo, #responsible_users_info, #linked_addresses_info").val("");
    }

    static RenderID(elem){
        var element = $(elem).parent().parent();
        var id = element.data("id");

        $("#CallIDInfo").val(id);
    }

    static selectAddress(elem) {
        $(elem).toggleClass('active');
        var linked_addresses = [];
        $("#CreateInfolog .linked_addresses .address.active").each(function(key, elem) {
            var uid = $(this).attr('data-uid');
            if (uid) {
                linked_addresses.push(uid);
            }
        });
        linked_addresses.join(",");
        $("#linked_addresses_info").val(linked_addresses);
        console.log(linked_addresses);
    }

    static selectResponsible(elem) {
        $(elem).toggleClass('active');
        var responsible_users = [];
        $("#CreateInfolog .responsible_users .user.active").each(function(key, elem) {
            var uid = $(this).attr('data-uid');
            if (uid) {
                responsible_users.push(uid);
            }
        });
        responsible_users.join(",");
        $("#responsible_users_info").val(responsible_users);
        console.log(responsible_users);
    }

    LoadStatuses() {
        $("#inputTypeInfo").change(function() {
            Infolog.updateStatuses();
        });

        $.get(Infolog.InfologUrl + "?action=getStatuses", function(data) {
            window.statuses = data;
            Infolog.updateStatuses();
        });
    }

    static updateStatuses() {
        var info_type = $("#inputTypeInfo").find(":selected").val();
        var statuses = window.statuses[info_type];
        var defaultStatus = window.statuses.defaults[info_type];
        var html = "";
        $.each(statuses, function(index, value) {
            html += '<option value="' + index + '">' + value + '</option>'
        });
        $("#inputStatusInfo").html(html);
        $("#inputStatusInfo > option").each(function() {
            if ($(this).val() == defaultStatus) {
                $(this).attr("selected", "selected");
            }
        });
    }

}
