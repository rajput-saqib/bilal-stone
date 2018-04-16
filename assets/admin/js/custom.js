function deleteRow(id, path)
{
    if(confirm("Are You Sure ?"))
    {
        $.ajax({
            type : "POST",
            url : path,
            data : "&id=" + id,
            success : function ()
            {
                $("#row-" + id).hide();
                showNotification('saqib rajput', 'red');
            }
        });
    }
}

function showNotification(msg, color)
{
    noty();
    $('.noty_text').html(msg).css('color', color);
}

function statusRow(id, path, status)
{
    var check = false;
    //console.log(id+"|"+path+"|"+status);
    if(status == '-1')
    {
        if(confirm('Are You Sure?'))
        {
            check = true;
        }
    }
    else
    {
        check = true;
    }

    if(check == true) {
        $.ajax({
            type : "POST",
            url : path,
            data : "&id=" + id + "&status=" + status,
            success : function (data)
            {
                if (status == '-1') {
                    $("#row-" + id).hide();
                    showNotification('Row Deleted Successfully.', 'red');
                }
                else {
                    location.reload();
                }
            }
        });
    }
}

function formValidation(form_id) //            validation Type = required, selectBox, email, password match, multi-checkbox
{
    $('#' + form_id).find('.error-border').removeClass('error-border');
    var form = document.getElementById(form_id);

    for (var i = 0 ; i < form.length ; i++)
    {
        var id = form.elements[i].id;
        var className = form.elements[i].className;

        //alert("ID :"+id+" Class:"+className);

        if(check_empty(className, id) || check_email(className, id) || check_number(className, id) || check_alphanumeric(className, id))
        {
            return false;
        }
   }

    return true;
}

function check_empty(className, id)
{
    var value = $("#" + id).val();
    var check = className.indexOf("c-e");

    $("#" + id).parent().find('.error-class').remove();
    if (check > 0)
    {
        if(value == "")
        {
            $("#" + id).focus().addClass('error-border');
            $("#" + id).parent().find('.error-class').remove();
            $("#" + id).parent().append( "<span class='error-class'>Required.</span>");
            return true;
        }
    }
}

function check_email(className, id)
{
    var value = $("#" + id).val();
    var check = className.indexOf("c-em");

    if (check > 0)
    {
        var re = /^[a-zA-Z0-9_\.\-]+\@([a-zA-Z0-9\-]+\.)+[a-zA-Z0-9]{2,4}$/;
        if(!re.test(value))
        {
            $("#" + id).focus().addClass('error-border');
            $("#" + id).parent().find('.error-class').remove();
            $("#" + id).parent().append( "<span class='error-class'>Enter Your Correct Email.</span>");
            return true;
        }
        else
        {
            $("#" + id).parent().find('.error-class').remove();
        }

    }
}

function check_number(className, id)
{
    var value = $("#" + id).val();
    var check = className.indexOf("check-number");

    if (check > 0)
    {
        var re = /^\d+$/;
        if(!re.test(value))
        {
            $("#" + id).focus().addClass('error-border');
            $("#" + id).parent().find('.error-class').remove();
            $("#" + id).parent().append( "<span class='error-class'>Enter Number Only.</span>");

            return true;
        }
        else
        {
            $("#" + id).parent().find('.error-class').remove();
        }
    }
}

function check_alphanumeric(className, id)
{
    var value = $("#" + id).val();
    var check = className.indexOf("check-alphanumeric");

    if (check > 0)
    {
        var re = /^[a-z0-9]+$/i;
        if(!re.test(value))
        {
            $("#" + id).focus().addClass('error-border');
            $("#" + id).parent().find('.error-class').remove();
            $("#" + id).parent().append( "<span class='error-class'>Special Charector Not Allowd.</span>");

            return true;
        }
        else
        {
            $("#" + id).parent().find('.error-class').remove();
        }
    }
}

function validation_two_fields(param1, param2)
{
    var id1 = param1;
    var value1 = $("#" + param1).val();

    var id2 = param2;
    var value2 = $("#" + param2).val();

    if (value1 != value2) {
        $("#" + id1).focus().addClass('error-border');
        $("#" + id2).focus().addClass('error-border');
        return true;
    }
}

function validation_checkbox(param)
{
    var id = param;

    if (!$('#' + id).is(':checked')) {
        $("#" + id).focus().parent().addClass('error-border');
        return false;
    }
    return true;
}

function validation_radio(param)
{
    var name = param;
    var id = param;

    var value = $('input:radio[name=gender_1]:checked').val();

    if (value != 'male' && value != 'female') {
        $("#" + id).focus().parent().addClass('error-border');
        return false;
    }
    return true;
}


function createValidation(id)
{
    if($("#num_"+id).is(":checked"))
    {
        $("#type_" + id).focus().addClass('[c-e]');
    }
    else
    {
        $("#type_" + id).focus().removeClass('[c-e]');
    }
}

function deleteRowInvoice(id)
{
    $("."+id).fadeOut();

    setTimeout(function(){
        $("."+id).html("");
    },1000);
}

function newTab(path, result)
{
    if(result == "1")
    {
        var win = window.open(path);

        if (win){
            win.focus();
        }
        else{
            alert('Please allow popups for this site');
        }
    }
    else
    {
        alert("File Not Download Try Again.");
    }

}

function pagination(query, pageNo, controller)
{
    $.ajax({
        type : "POST",
        url  : "/setPagination",
        data : "&query="+query+"&pageNo="+pageNo+"&controller="+controller,
        success:function(data)
        {
            $("#show-pagination").html(data);
        }
    });
}


function addMore(page)
{
    var no = $('.add-more').length;
    var html = jQuery($('#add-more-0').clone());

    if(page == 'products')
    {
        html.removeAttr('id').attr('id', 'add-more-'+no);
        html.find('#items-0').removeAttr('id').attr('id', 'items-'+no);
        html.find('#qty-0').removeAttr('id').attr('id', 'qty-'+no).val('');
        html.find('#add-more-btn').removeClass('icon-plus').addClass('icon-close').removeAttr('id').removeAttr('onclick').attr('onclick', 'removeRow("add-more-'+no+'")');
    }

    if(page == 'invoice')
    {
        html.removeAttr('id').attr('id', 'add-more-'+no);
        html.find('#product-0').removeAttr('id').attr('id', 'product-'+no);
        html.find('#qty-0').removeAttr('id').attr('id', 'qty-'+no).val('').removeAttr('onkeyup').attr('onkeyup', 'setAmount("'+no+'")');
        html.find('#rate-0').removeAttr('id').attr('id', 'rate-'+no).val('').removeAttr('onkeyup').attr('onkeyup', 'setAmount("'+no+'")');
        html.find('#price-0').removeAttr('id').attr('id', 'price-'+no).val('');
        html.find('#add-more-btn').removeClass('icon-plus').addClass('icon-close').removeAttr('id').removeAttr('onclick').attr('onclick', 'removeRow("add-more-'+no+'")');
    }

    if(page == 'stock')
    {
        html.removeAttr('id').attr('id', 'add-more-'+no);
        html.find('#item-0').removeAttr('id').attr('id', 'item-'+no);
        html.find('#qty-0').removeAttr('id').attr('id', 'qty-'+no).val('').removeAttr('onkeyup').attr('onkeyup', 'setAmount("'+no+'")');
        html.find('#rate-0').removeAttr('id').attr('id', 'rate-'+no).val('').removeAttr('onkeyup').attr('onkeyup', 'setAmount("'+no+'")');
        html.find('#price-0').removeAttr('id').attr('id', 'price-'+no).val('');
        html.find('#add-more-btn').removeClass('icon-plus').addClass('icon-close').removeAttr('id').removeAttr('onclick').attr('onclick', 'removeRow("add-more-'+no+'")');
    }

    if(page == 'payment')
    {
        html.removeAttr('id').attr('id', 'add-more-'+no);
        html.find('#desc-0').removeAttr('id').attr('id', 'desc-'+no).val('');
        html.find('#price-0').removeAttr('id').attr('id', 'price-'+no).val('');
        html.find('#add-more-btn').removeClass('icon-plus').addClass('icon-close').removeAttr('id').removeAttr('onclick').attr('onclick', 'removeRow("add-more-'+no+'")');
    }

    if(page == 'stock-employee')
    {
        html.removeAttr('id').attr('id', 'add-more-'+no);
        html.find('#item-0').removeAttr('id').attr('id', 'item-'+no);
        html.find('#qty-0').removeAttr('id').attr('id', 'qty-'+no).val('');
        html.find('#add-more-btn').removeClass('icon-plus').addClass('icon-close').removeAttr('id').removeAttr('onclick').attr('onclick', 'removeRow("add-more-'+no+'")');
    }

    if(page == 'send-item-received')
    {
        html = jQuery($('#add-more-xx').clone());
        //$("#validNo").val($("#validNo").val()+no+'|');
        html.removeAttr('id').attr('id', 'add-more-'+no).addClass("add-more").show();

	    html.find('#product-no-xx').removeAttr('id').attr('id', 'product-no-'+no).html((no+1));
        html.find('#product-xx').removeAttr('id').attr('id', 'product-'+no).addClass('chosen').removeAttr('onchange').attr('onchange', 'addItems(this.value,"'+no+'")').val('');
        html.find('#qty-xx').removeAttr('id').attr('id', 'qty-'+no).val('').removeAttr('onkeyup').attr('onkeyup', 'calculateAmount("'+no+'")');
        html.find('#rate-xx').removeAttr('id').attr('id', 'rate-'+no).val('').removeAttr('onkeyup').attr('onkeyup', 'calculateAmount("'+no+'")');
        html.find('#price-xx').removeAttr('id').attr('id', 'price-'+no).val('');
        html.find('#add-more-btn').removeClass('icon-plus').addClass('icon-close').removeAttr('id').removeAttr('onclick').attr('onclick', 'removeInvoiceRow("'+no+'")');

        html.find('#item-section-xx').removeAttr('id').attr('id', 'item-section-'+no).hide();
    }

    if(page == 'sale-product')
    {
        html = jQuery($('#add-more-xx').clone());
        //$("#validNo").val($("#validNo").val()+no+'|');
        html.removeAttr('id').attr('id', 'add-more-'+no).addClass("add-more").show();

	    html.find('#product-no-xx').removeAttr('id').attr('id', 'product-no-'+no).html((no+1));
        html.find('#product-xx').removeAttr('id').attr('id', 'product-'+no).addClass('chosen').removeAttr('onchange').attr('onchange', 'addItems(this.value,"'+no+'")').val('');
        html.find('#qty-xx').removeAttr('id').attr('id', 'qty-'+no).val('').removeAttr('onkeyup').attr('onkeyup', 'calculateAmount("'+no+'")');
        html.find('#subTotal-xx').removeAttr('id').attr('id', 'subTotal-'+no).val('');
        html.find('#otherAmount-xx').removeAttr('id').attr('id', 'otherAmount-'+no).val('').removeAttr('onkeyup').attr('onkeyup', 'calculateAmount("'+no+'")');
        html.find('#price-xx').removeAttr('id').attr('id', 'price-'+no).val('');
        html.find('#add-more-btn').removeClass('icon-plus').addClass('icon-close').removeAttr('id').removeAttr('onclick').attr('onclick', 'removeInvoiceRow("'+no+'")');

        html.find('#item-section-xx').removeAttr('id').attr('id', 'item-section-'+no).hide();
    }

    if(page == 'supplier-invoice')
    {
        html = jQuery($('#add-more-xx').clone());
        html.removeAttr('id').attr('id', 'add-more-'+no).addClass("add-more").show();

	    html.find('#item-no-xx').removeAttr('id').attr('id', 'item-no-'+no).html((no));
        html.find('#items-xx').removeAttr('id').attr('id', 'item-'+no).addClass('chosen').val('');
        html.find('#qty-xx').removeAttr('id').attr('id', 'qty-'+no).val('').removeAttr('onkeyup').attr('onkeyup', 'setAmountSupplier("'+no+'")');
        html.find('#rate-xx').removeAttr('id').attr('id', 'rate-'+no).val('').removeAttr('onkeyup').attr('onkeyup', 'setAmountSupplier("'+no+'")');
        html.find('#price-xx').removeAttr('id').attr('id', 'price-'+no).val('');
        html.find('#add-more-btn').removeClass('icon-plus').addClass('icon-close').removeAttr('id').removeAttr('onclick').attr('onclick', 'removeRowSupplier("'+no+'")');

        html.find('#item-section-xx').removeAttr('id').attr('id', 'item-section-'+no).hide();
    }

    if(page == 'payment-sale')
    {
        html.removeAttr('id').attr('id', 'add-more-'+no);
        html.find('#desc-0').removeAttr('id').attr('id', 'desc-'+no).val('');
        html.find('#price-0').removeAttr('id').attr('id', 'price-'+no).val('');
        html.find('#add-more-btn').removeClass('icon-plus').addClass('icon-close').removeAttr('id').removeAttr('onclick').attr('onclick', 'removeInvoiceRow("'+no+'")');
    }

    if(page == 'expense-invoice')
    {

        html = jQuery($('#add-more-xx').clone());
        //console.log(html);
        html.removeAttr('id').attr('id', 'add-more-'+no).addClass("add-more").show();

	    html.find('#item-no-xx').removeAttr('id').attr('id', 'item-no-'+no).html((no));
        html.find('#expense_type-xx').removeAttr('id').attr('id', 'expense_type-'+no).addClass('chosen').val('').removeAttr('onchange').attr('onchange', 'getStaff(this.value,'+no+')');
        html.find('#staffId-div-xx').removeAttr('id').attr('id', 'staffId-div-'+no);
        html.find('#staffId-xx').removeAttr('id').attr('id', 'staffId-'+no).addClass('chosen').val('');
        html.find('#desc-xx').removeAttr('id').attr('id', 'desc-'+no).val('');
        html.find('#amount-xx').removeAttr('id').attr('id', 'amount-'+no).val('');
        html.find('#add-more-btn').removeClass('icon-plus').addClass('icon-close').removeAttr('id').removeAttr('onclick').attr('onclick', 'removeRowExpense("'+no+'")');
        html.find('#item-section-xx').removeAttr('id').attr('id', 'item-section-'+no).hide();
    }


    $('#add-more-container').append(html);


    if(page == 'send-item-received' || page == 'sale-product' || page == 'supplier-invoice' || page == 'expense-invoice') {
        setChosen();
    }
}

function removeRow(id)
{
    $('#'+id).slideUp('slow').html('');
}


function setAmount(no)
{
    var rate = parseFloat($('#rate-'+no).val());
    var qty = parseFloat($('#qty-'+no).val());
    var price = (!isNaN(qty) && !isNaN(rate)) ? (rate*qty) : '';

    $('#price-'+no).val(price);

    var total_amount = 0;
    var length = $('.add-more').length;

    for(var a = 0 ; a < length ; a++)
    {
        total_amount += parseInt($('#price-'+a).val());
    }

    $('#total_amount').val((!isNaN(total_amount)) ? (total_amount) : '');
}

function setTotalAmount()
{
    var total_amount = 0;
    var length = $('.add-more').length;

    for(var a = 0 ; a < length ; a++)
    {
        var price = parseFloat($('#price-'+a).val());
        if(!isNaN(price))
        {
            total_amount += price;
        }
    }

    $('#total_amount').val((!isNaN(total_amount)) ? (total_amount) : '');
}

function setTotalQty()
{
    console.log('123');
    var total_amount = 0;
    var length = $('.add-more').length;

    for(var a = 0 ; a < length ; a++)
    {
        var qty = parseFloat($('#qty-'+a).val());
        if(!isNaN(qty))
        {
            total_amount += qty;
        }
    }

    $('#total_amount').val((!isNaN(total_amount)) ? (total_amount) : '');
}

