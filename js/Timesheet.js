class Timesheet {

    static get SearchUrl() {
        return "/egroupware/threecx/graph/search.php";
    }

    static get TimesheetUrl() {
        return "/egroupware/threecx/graph/timesheet.php";
    }

    constructor() {
        $(document).on("click", "#CreateTimesheet .address", function(){
            Timesheet.selectAddress(this)
        });
        this.InputAddressTS();
        this.SubmitForm();
    }

    SubmitForm() {
        $("#CreateTimesheet button").click(function(e) {
            e.preventDefault();
            var data = $("#CreateTimesheet form").serialize();
            $.ajax({
                type: "POST",
                url: Timesheet.TimesheetUrl + "?action=newTimesheet",
                data: data,
                // dataType: "json",
                success: function(data) {
                    if (data.response = "success") {
                        var call_id = $('input#CallIDTS').val();
                        $(".call[data-id='"+call_id+"']").attr("data-marked", "true");
                        $('#CreateTimesheet').modal('toggle');
                    }
                },
                error: function() {
                    alert('error handling here');
                }
            });
        });
    }

    InputAddressTS() {
        $("#inputAddressTS").bind("click", function() {
            if (this.value == "") {
                return "";
            }
            $.get(Timesheet.SearchUrl + "?query=" + this.value + "&app=addressbook", function(data) {
                var html = "";
                for (var i = 0; i < data.length; i++) {
                    if (i == 10) {
                        break;
                    }
                    var address = data[i];
                    var hasId = false;
                    $("#CreateTimesheet .linked_addresses .address.active").each(function(key, elem) {
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
                $("#CreateTimesheet .linked_addresses .address").not('.active').remove();
                $("#CreateTimesheet .linked_addresses").append(html);
            });
        });
        $('#inputAddressTS').keypress(function(e) {
            if (e.which == 13) {
                $('#inputAddressTS').click();
                return false;
            }
        });
    }

    static Create(elem) {
        Timesheet.RenderID(elem);

        var call_id = $(elem).parent().parent().find(".call_id");
        var destination = $(elem).parent().parent().find(".destination");

        var regExp = /\(([^)]+)\)/;
        var matches = regExp.exec(call_id.data("title"));
        //matches[1] contains the value between the parentheses
        var TimesheetTitle;
        if (matches && matches[1].length <= 3){
            TimesheetTitle = destination.data("title");
        } else {
            TimesheetTitle = call_id.data("title");
        }

        var number = call_id.data("number");
        $("#inputTitleTS, #inputAddressTS").val(TimesheetTitle);
        $("#inputNumberTS").val(number);
        $("#CreateTimesheet .linked_addresses .address").remove();
        $("#descriptionTS, #linked_addresses_ts").val("");

        $('#inputCategory option[value="318"]').attr('selected','selected');
        $('#inputStatusTS option[value="4"]').attr('selected','selected');
        $('#inputAddressTS').click();
    }

    static RenderID(elem){
        var element = $(elem).parent().parent();
        var id = element.data("id");

        $("#CallIDTS").val(id);
    }

    static selectAddress(elem) {
        $(elem).toggleClass('active');
        var linked_addresses = [];
        $("#CreateTimesheet .linked_addresses .address.active").each(function(key, elem) {
            var uid = $(this).attr('data-uid');
            if (uid) {
                linked_addresses.push(uid);
            }
        });
        linked_addresses.join(",");
        $("#linked_addresses_ts").val(linked_addresses);
        console.log(linked_addresses);
    }

}
