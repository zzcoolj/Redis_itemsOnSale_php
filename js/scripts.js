// Empty JS for your own code to be here


$(document).ready(function () {

    printAllProduct();

    $("#submit").click(function () {
        var productName = $("#name").val();
        var productPrice = $("#price").val();
        var productCategory = $("#category").val();
        var productDescription = $("textarea#description").val();
        var dataContainer = "productName=" + productName + "&productPrice=" + productPrice + "&productCategory=" + productCategory + "&productDescription=" + productDescription;
        $.ajax({
            type: "POST",
            url: "addProduct.php",
            data: dataContainer,
            success: function (data) {
                appendProductToTable(data);
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert(xhr.status + " : " + thrownError);
            }
        });
        console.log("submit");
    });
});

function printAllProduct() {
    $(document).ready(function () {
        $.ajax({
            type: "POST",
            url: "getAllProduct.php",
            success: function (data) {
                $("#contentContainer").html("<table class='table'><thead> <tr> <th>Name </th> <th>Price</th><th>category</th><th>Description</th></tr></thead><tbody id='table'>");
                console.log(data);
                parsedData = jQuery.parseJSON(data);
                console.log(parsedData[0]);
                for (var i = 0; i < parsedData.length; i++) {
                    $("#table").append("<tr> <td>" + parsedData[i].name + "</td> <td>" + parsedData[i].price + "</td> <td>" + parsedData[i].category + "</td><td>" + parsedData[i].description + "</td> </tr>");
                }


            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert(xhr.status + " : " + thrownError);
            }
        });
    });
}

function appendProductToTable(productKey) {
    $(document).ready(function () {
        console.log(productKey);
        $.ajax({
            type: "POST",
            url: "getProductByKey.php",
            data: "productKey=" + productKey,
            success: function (data) {
                console.log(data);
                parsedData = jQuery.parseJSON(data);
                console.log(parsedData);
                $("#table").append("<tr> <td>" + parsedData.name + "</td> <td>" + parsedData.price + "</td> <td>" + parsedData.category + "</td><td>" + parsedData.description + "</td> </tr>");
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert(xhr.status + " : " + thrownError);
            }
        });
    });
}